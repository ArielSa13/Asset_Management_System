pipeline {
  agent any

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Deploy') {
        steps {
            sh '''
            echo "Fix SSH trust + deploy"

            git config --global --add safe.directory /srv/apps/asset-staging

            mkdir -p ~/.ssh
            ssh-keyscan github.com >> ~/.ssh/known_hosts

            cd /srv/apps/asset-staging
            git pull origin develop

            docker compose up -d --build
            '''
        }
        }

  }
}