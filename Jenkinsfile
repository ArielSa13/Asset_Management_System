pipeline {
    agent any

    stages {

        stage('Deploy Production') {
            steps {
                sh '''
                    echo "=== PRODUCTION DEPLOY START ==="

                    git config --global --add safe.directory /srv/apps/asset-prod

                    cd /srv/apps/asset-prod

                    git fetch origin
                    git reset --hard origin/main

                    docker compose up -d --build
                    echo "=== PRODUCTION DEPLOY DONE ==="
                '''
            }
        }

    }
}
