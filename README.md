## Install
    # Install LDAP PHP extension for testing wpdirauth-posixgroup
    apt install php-ldap
    
    # install wp-cli
    wp core download --path=wp
    cd wp
    wp core config  # create db first
    wp core install  # needs initial credentials
    cd wp-content/themes
    ln -s ../../../aktiv2014
    
    cd ../../../dns2015
    yarn
    
    # Install public plugins listed below
    cd ../wp
    wp plugin install ...
    
    cd ../wp/wp-content/plugins
    git submodule update --init
    ln -s ../../plugins/wpdirauth-posixgroup
    
    wp core language install nb_NO
    wp core language activate nb_NO
    wp core language update

## Plugins
    # install plugins with 'wp plugin install'
    disqus-comment-system
    feed-key
    opengraph
    restricted-site-access
    user-role-editor

## Development tasks
    fab build
    fab watch
    
## Deployment
    fab deploy
