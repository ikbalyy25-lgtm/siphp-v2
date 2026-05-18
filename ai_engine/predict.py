import sys
import joblib
import pandas as pd
import os
import json

def predict(nama_barang, harga_hari_ini):
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    clean_name = nama_barang.strip().replace(" ", "_").lower()
    model_path = os.path.join(BASE_DIR, 'models', f"model_{clean_name}.pkl")

    if not os.path.exists(model_path):
        return json.dumps({"error": "Model tidak ditemukan"})

    model = joblib.load(model_path)
    
    # Ambil tanggal besok
    besok = pd.Timestamp.now() + pd.Timedelta(days=1)
    
    # Buat data input untuk prediksi
    X_input = pd.DataFrame([{
        'bulan': besok.month,
        'hari': besok.day,
        'hari_dalam_minggu': besok.dayofweek,
        'harga_kemarin': float(harga_hari_ini)
    }])

    prediksi = model.predict(X_input)[0]
    return json.dumps({"prediksi": round(float(prediksi))})

if __name__ == "__main__":
    # Menangkap argumen dari Laravel
    print(predict(sys.argv[1], sys.argv[2]))