pipeline {
    agent none
    
    stages {
        
        stage('Composer') {
            agent {
                docker { image 'fritsstegmann/jenkins-build-php' }
            }
            
            steps {
                
                sh 'composer install'
            }
        }

        stage('Yarn') {
            agent {
                docker { image 'fritsstegmann/jenkins-build-php' }
            }
            steps {
                sh 'yarn'
            }
        }
        stage('Test') {
            agent {
                docker { image 'fritsstegmann/jenkins-build-php' }
            }
            steps {

                sh "./vendor/bin/phpunit --log-junit storage/build/phpunit-report.xml --coverage-html storage --coverage-clover storage/build/coverage.xml" 
                
                step([

                    $class: 'CloverPublisher',
                    cloverReportDir: 'storage/build',
                    cloverReportFileName: 'coverage.xml',
                    healthyTarget: [methodCoverage: 70, conditional Coverage: 80, statementCoverage: 80], 
                    unhealthyTarget: [methodCoverage: 50, conditional Coverage: 50, statement Coverage: 50], 
                    failingTarget: [method Coverage: 30, conditional Coverage: 30, statementCoverage: 30]
                ])
            }
            
            post {
                
                always {
                    
                    junit 'storage/build/*.xml'
                }
            }
        }

        stage('SonarQube Analysis') {

            agent {
                
                label 'main'
            }
            
            steps {
                script {

                    // requires SonarQube Scanner 2.8+
                    scannerHome = tool 'SonarQube Scanner 6.0.0.4432'
                }

                withSonarQubeEnv('SonarQube') {
                    sh "${scannerHome}/bin/sonar-scanner"
                }
            }
        }
        
        stage('Npm Production') {
            agent {

                docker { image 'fritsstegmann/jenkins-build-php' }
            }

            steps {
                
                sh 'npm run production'
            }
        }
    }
}