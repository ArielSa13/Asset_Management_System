pipeline {
  agent any

  stages {

    stage('Deploy') {
  steps {
    sh '''
      echo "Deploy START"

      git config --global --add safe.directory /srv/apps/asset-staging

      cd /srv/apps/asset-staging

      git pull origin develop
      docker-compose down
      docker-compose up -d --build
    '''
  }
}

  }
}