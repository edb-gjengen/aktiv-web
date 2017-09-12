from fabric.operations import local as lrun
from fabric.api import cd, env, lcd, run

env.use_ssh_config = True
env.forward_agent = True
env.hosts = ['dreamcast.neuf.no']
env.user = 'gitdeploy'

project_path = '/var/www/neuf.no/aktivweb'
theme_path = 'aktiv2014/'
gulp_file = '{}gulpfile.js'.format(theme_path)

def install():
    with lcd(theme_path):
        lrun('yarn')

def _gulp_cmd(cmd):
    lrun('gulp --gulpfile={} {}'.format(gulp_file, cmd))

def build():
    _gulp_cmd('build')

# def i18n():
#     _gulp_cmd('i18n')

def watch():
    _gulp_cmd('watch')

def deploy():
    with cd(project_path):
        run('git pull')

def upgrade_wp():
    with cd(project_path):
        run('wp core update')
        run('wp plugin update --all')
        run('wp theme update --all')
        run('wp core language update')
