import pandas as pd
import lightgbm as lgb
import joblib
import os
import numpy as np
import warnings

# Abaikan warning yang tidak perlu
warnings.filterwarnings('ignore')

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATA_PATH = os.path.join(BASE_DIR, 'data_fixed.csv')
MODEL_DIR = os.path.join(BASE_DIR, 'models')

if not os.path.exists(MODEL_DIR):
    os.makedirs(MODEL_DIR)

def train_models():
    if not os.path.exists(DATA_PATH):
        print(f"Error: File {DATA_PATH} tidak ditemukan!")
        return

    # Load data
    df = pd.read_csv(DATA_PATH)
    df['tanggal'] = pd.to_datetime(df['tanggal'])
    
    # Ambil daftar komoditas (semua kolom kecuali tanggal)
    komoditas_list = [col for col in df.columns if col != 'tanggal']

    for item in komoditas_list:
        print(f"Melatih AI: {item}")
        
      
        df[item] = pd.to_numeric(df[item].astype(str).str.replace(r'[^\d.]', '', regex=True), errors='coerce')
        
        data_item = df[['tanggal', item]].copy().sort_values('tanggal')
        
        # 2. Hapus baris yang harganya kosong setelah dibersihkan
        data_item = data_item.dropna(subset=[item])

        if len(data_item) < 5:
            print(f"Skipping {item}: Data terlalu sedikit (minimal 5 baris).")
            continue
        
        # 3. Feature Engineering
        data_item['bulan'] = data_item['tanggal'].dt.month
        data_item['hari'] = data_item['tanggal'].dt.day
        data_item['hari_dalam_minggu'] = data_item['tanggal'].dt.dayofweek
        data_item['harga_kemarin'] = data_item[item].shift(1)
        
       
        data_final = data_item.dropna()
        
        X = data_final[['bulan', 'hari', 'hari_dalam_minggu', 'harga_kemarin']]
        y = data_final[item]

        # 4. Konfigurasi Model LightGBM
        model = lgb.LGBMRegressor(
            n_estimators=500,
            learning_rate=0.05,
            random_state=42,
            verbosity=-1
        )
        
        model.fit(X, y)

        # 5. Simpan Model
        # Ganti spasi dengan underscore dan jadikan lowercase agar konsisten dengan Laravel
        clean_name = item.strip().replace(" ", "_").lower()
        model_name = f"model_{clean_name}.pkl"
        joblib.dump(model, os.path.join(MODEL_DIR, model_name))
        print(f"Selesai! Model disimpan: {model_name}")

if __name__ == "__main__":
    train_models()