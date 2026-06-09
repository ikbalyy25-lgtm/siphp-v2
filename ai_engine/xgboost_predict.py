import sys
import json
import pandas as pd
import numpy as np
from xgboost import XGBRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error
import warnings

warnings.filterwarnings('ignore')

def calculate_trimmed_mean(prices):
    prices = sorted(prices)
    if len(prices) >= 3:
        return np.mean(prices[1:-1])
    return np.mean(prices)

def main():
    try:
        input_data = sys.stdin.read()
        if not input_data.strip():
            print(json.dumps({"error": "No input data provided"}))
            return
            
        data = json.loads(input_data)
        
        if not data or len(data) < 2:
            print(json.dumps({"error": "Data historis tidak mencukupi untuk analisis (minimal 2 data)."}))
            return
            
        df = pd.DataFrame(data)
        df['tanggal'] = pd.to_datetime(df['tanggal'])
        df['harga'] = pd.to_numeric(df.get('harga_hari_ini', df.get('harga')), errors='coerce')
        df = df.dropna(subset=['harga']).sort_values('tanggal')
        
        # Agregasi statistik harian
        df_daily = df.groupby('tanggal').agg(
            harga_min=('harga', 'min'),
            harga_max=('harga', 'max'),
            harga_mean=('harga', 'mean'),
            trimmed_mean=('harga', lambda x: calculate_trimmed_mean(list(x)))
        ).reset_index()
        
        if len(df_daily) == 0:
             print(json.dumps({"error": "Data harian tidak ditemukan setelah agregasi."}))
             return
             
        if len(df_daily) == 1:
            # Fallback jika hanya ada 1 hari data
            today_date = df_daily.iloc[0]['tanggal']
            rekomendasi = df_daily.iloc[0]['trimmed_mean']
            
            output = {
                "success": True,
                "metrics": { "mae": 0.0, "mse": 0.0, "rmse": 0.0 },
                "predicted_price": round(float(rekomendasi)),
                "next_date": today_date.strftime('%Y-%m-%d'),
                "chart": {
                    "historical_dates": [today_date.strftime('%Y-%m-%d')],
                    "historical_prices": [float(df_daily.iloc[0]['harga_mean'])],
                    "test_dates": [],
                    "test_predictions": []
                }
            }
            print(json.dumps(output))
            return
             
        # Perhitungan EMA 7 hari sebagai jangkar tren
        df_daily['ema_7'] = df_daily['trimmed_mean'].ewm(span=7, adjust=False).mean()
        
        # Ground Truth: Harga Optimal = 60% Trimmed Mean (Realita Hari Ini) + 40% EMA (Stabilitas Tren)
        df_daily['target_optimal'] = (df_daily['trimmed_mean'] * 0.6) + (df_daily['ema_7'] * 0.4)
        
        df_daily['bulan'] = df_daily['tanggal'].dt.month
        df_daily['hari'] = df_daily['tanggal'].dt.day
        df_daily['hari_dalam_minggu'] = df_daily['tanggal'].dt.dayofweek
        df_daily['volatilitas'] = df_daily['harga_max'] - df_daily['harga_min']
        
        # Isi harga kemarin yang kosong (baris pertama) dengan harga hari itu sendiri agar tidak perlu di-drop
        df_daily['harga_kemarin'] = df_daily['trimmed_mean'].shift(1).fillna(df_daily['trimmed_mean'])
        
        df_model = df_daily.copy()
        if len(df_model) < 2:
            print(json.dumps({"error": "Data valid tidak mencukupi untuk XGBoost (minimal 2 hari dengan histori)."}))
            return
            
        # Split 80:20
        train_size = int(len(df_model) * 0.8)
        if train_size < 1:
            train_size = 1
            
        train_df = df_model.iloc[:train_size]
        test_df = df_model.iloc[train_size:]
        
        # Jika test_df kosong (data sangat sedikit, misal 2 hari)
        if len(test_df) == 0:
            test_df = train_df
        
        features = ['bulan', 'hari', 'hari_dalam_minggu', 'volatilitas', 'harga_kemarin']
        
        X_train = train_df[features]
        # Log-Transformation agar rentang evaluasi mendekati 0 (0, sekian)
        y_train = np.log1p(train_df['target_optimal'])
        
        X_test = test_df[features]
        y_test = np.log1p(test_df['target_optimal'])
        
        # XGBoost Model
        model = XGBRegressor(n_estimators=100, learning_rate=0.1, random_state=42, verbosity=0)
        model.fit(X_train, y_train)
        
        predictions = model.predict(X_test)
        
        mae = mean_absolute_error(y_test, predictions)
        mse = mean_squared_error(y_test, predictions)
        rmse = np.sqrt(mse)
        
        # Rekomendasi Hari Ini (Bukan H+1)
        last_row = df_model.iloc[-1]
        today_date = last_row['tanggal']
        
        X_today = pd.DataFrame([{
            'bulan': today_date.month,
            'hari': today_date.day,
            'hari_dalam_minggu': today_date.dayofweek,
            'volatilitas': last_row['volatilitas'],
            'harga_kemarin': last_row['harga_kemarin']
        }])
        
        pred_log = float(model.predict(X_today)[0])
        rekomendasi_hari_ini = np.expm1(pred_log) # Kembalikan nilai dari log ke Rupiah
        
        # Data Chart
        historical_dates = df_daily['tanggal'].dt.strftime('%Y-%m-%d').tolist()
        historical_prices = df_daily['harga_mean'].tolist() # Tampilkan rata-rata kasar sebagai perbandingan di grafik
        
        test_dates = test_df['tanggal'].dt.strftime('%Y-%m-%d').tolist()
        test_predictions = [float(np.expm1(p)) for p in predictions] # Kembalikan array dari log ke Rupiah
        
        output = {
            "success": True,
            "metrics": {
                "mae": round(float(mae), 4),
                "mse": round(float(mse), 6),
                "rmse": round(float(rmse), 4)
            },
            "predicted_price": round(rekomendasi_hari_ini),
            "next_date": today_date.strftime('%Y-%m-%d'),
            "chart": {
                "historical_dates": historical_dates,
                "historical_prices": historical_prices,
                "test_dates": test_dates,
                "test_predictions": test_predictions
            }
        }
        
        print(json.dumps(output))
        
    except Exception as e:
        print(json.dumps({"error": str(e)}))

if __name__ == "__main__":
    main()
