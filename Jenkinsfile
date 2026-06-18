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
    
              # Paksa hapus container lama berdasarkan nama spesifiknya sebagai pengaman tambahan
              docker rm -f asset_prod_app || true
    
              # Tambahkan opsi -p untuk mengunci nama project
              docker-compose -p asset_prod down
              docker-compose -p asset_prod up -d --build --force-recreate
    
              docker image prune -f
    
              echo "=== DEPLOY DONE ==="
            '''
          }
        }
    }
}
