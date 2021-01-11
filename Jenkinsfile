pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building...'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
                sh 'ls -al'
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'DEPLOY@WEB_HOST_0',
                            transfers: [
                                sshTransfer(
                                    cleanRemote: true,
                                    excludes: '',
                                    execCommand: 'pwd; printenv; ls -al',
                                    execTimeout: 120000,
                                    flatten: false,
                                    makeEmptyDirs: true,
                                    noDefaultExcludes: false,
                                    patternSeparator: '[, ]+',
                                    remoteDirectory: 'atac',
                                    remoteDirectorySDF: false,
                                    removePrefix: '',
                                    sourceFiles: '**'
                                )
                            ],
                            usePromotionTimestamp: false,
                            useWorkspaceInPromotion: false,
                            verbose: true
                        )
                    ]
                )
            }
        }
    }
}
