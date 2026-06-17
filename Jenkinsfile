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
            echo "Deploy from Jenkins workspace"

            git config --global --add safe.directory /var/jenkins_home/workspace/Stag_Asset

            docker compose down || true
            docker compose up -d --build
            '''
        }
        }

  }
}