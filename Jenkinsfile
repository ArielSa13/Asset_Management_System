pipeline {
  agent any

  stages {

    stage('Deploy') {
      steps {
        sh '''
          echo "Deploy START"

          cd /srv/apps/asset-staging

          git pull origin develop

          docker-compose down || true
          docker-compose up -d --build

          echo "Deploy SUCCESS"
        '''
      }
    }

  }
}