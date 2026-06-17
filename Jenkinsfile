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
          echo "Deploy jalan..."
          git config --global --add safe.directory /srv/apps/asset-staging
          cd /srv/apps/asset-staging
          git pull origin develop
          docker compose up -d --build
        '''
      }
    }

  }
}