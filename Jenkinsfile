pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                // Mengambil kode terbaru dari branch main di GitHub
                git branch: 'main',
                    url: 'https://github.com/ArielSa13/Asset_Management_System.git'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                    echo "=== DEPLOY START ==="

                    # 1. Hentikan dan hapus container lama beserta network internalnya (jika ada)
                    docker-compose down

                    # 2. Bangun ulang image dan jalankan container baru di background
                    docker-compose up -d --build --force-recreate

                    # 3. Bersihkan image-image tua yang sudah tidak terpakai (menghemat disk)
                    docker image prune -f

                    echo "=== DEPLOY DONE ==="
                '''
            }
        }
    }
}
