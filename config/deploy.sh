if [[ $TRAVIS_PULL_REQUEST = "false" ]] && [[ $TRAVIS_BRANCH = 'master' ]]; then

    source ~/.rvm/scripts/rvm
    rvm install 2.3
    rvm use 2.3

    ruby -v

    echo 'decrypting deploy key'
    openssl aes-256-cbc -k ${DEPLOY_KEY} -in 'config/coderdojo_deploy_enc_travis' -d -a -out 'config/coderdojo_deploy'
    chmod 600 'config/coderdojo_deploy'

    gem update --system 3.2.3
    gem install bundler

    echo 'preparing Capistrano'
    bundle install

    echo 'Deploying!'
    bundle exec cap production deploy
fi
