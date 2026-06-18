pipeline {
  agent any

  stages {

    stage('Checkout') {
      steps {
        git branch: 'main',
            url: 'https://github.com/ArielSa13/Asset_Management_System.git'
      }
    }

    stage('Deploy') {
      steps {
        sh '''
          echo "DEPLOY START"

          docker-compose up -d --build --force-recreate

          docker image prune -f

          echo "DEPLOY DONE"
        '''
      }
    }
  }
}
