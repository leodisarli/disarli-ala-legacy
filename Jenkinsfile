// Replace all follow variables values before run on Jenkins
// {{your-repository/nginx-image}}
// {{your-repository/php-image}}
// {{your-uuid-credential-here}}
node {
    def app

    stage ('Clone Repository') {
         checkout scm
    }

    stage ('Install Dependencies') {
        sh 'composer install --prefer-dist'
        sh 'cp .env.dist .env'
    }

    stage ('Running PHP Lint') {
        sh 'composer lint'
    }

    stage ('Running Code Snifer') {
        sh 'composer cs'
    }

    stage ('Running Mess Detector') {
        sh 'composer mess'
    }

    stage ('Unit Tests') {
        sh 'composer test-xml'
        step([
            $class: 'CloverPublisher',
            cloverReportDir: './coverage',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 100, conditionalCoverage: 100, statementCoverage: 100],
            unhealthyTarget: [methodCoverage: 99, conditionalCoverage: 99, statementCoverage: 99],
            failingTarget: [methodCoverage: 98, conditionalCoverage: 98, statementCoverage: 98]
        ])
    }

    stage ('Build container Nginx') {
        if (env.BRANCH_NAME.startsWith("release-") || env.BRANCH_NAME ==~ /(master)/) {
            app = docker.build('{{your-repository/nginx-image}}', '-f docker/nginx/Dockerfile .')
        }
    }
    stage('Publish to DockerHub Nginx') {
        if (env.BRANCH_NAME.startsWith("release-")) {
            withDockerRegistry([credentialsId: '{{your-uuid-credential-here}}', url: 'https://registry.hub.docker.com']) {
                app.push('nginx-homolog')
            }
        }
        if (env.BRANCH_NAME ==~ /(master)/) {
            withDockerRegistry([credentialsId: '{{your-uuid-credential-here}}', url: 'https://registry.hub.docker.com']) {
                app.push('nginx')
            }
        }
    }

    stage ('Build container PHP') {
        if (env.BRANCH_NAME.startsWith("release-") || env.BRANCH_NAME ==~ /(master)/) {
            app = docker.build('{{your-repository/php-image}}', '-f docker/php/Dockerfile .')
        }
    }
    stage('Publish to DockerHub PHP') {
        if (env.BRANCH_NAME.startsWith("release-")) {
            withDockerRegistry([credentialsId: '{{your-uuid-credential-here}}', url: 'https://registry.hub.docker.com']) {
                app.push('php-homolog')
            }
        }
        if (env.BRANCH_NAME ==~ /(master)/) {
            withDockerRegistry([credentialsId: '{{your-uuid-credential-here}}', url: 'https://registry.hub.docker.com']) {
                app.push('php')
            }
        }
    }
    
}

