#!/usr/bin/env python3

import os
import shutil
import tarfile
import base64
import re
import argparse

# define red, yellow, green, and reset colors for printing
red = "\033[1;31m"
yellow = "\033[1;33m"
green = "\033[1;32m"
reset = "\033[0;0m"

parser = argparse.ArgumentParser(description='Build the shell package and embed into manager.php')

parser.add_argument('-r', '--random', action='store_true', help='give the php file a random name')

args = parser.parse_args()

if not os.path.isfile("build.py"):
    print("This script must be run from the root of the project")
    exit(1)

try:
    if not os.path.exists('build'):
        print("Creating build folder...")
        os.mkdir('build')
except Exception as e:
    print(f"{red}Failed to create build folder. Reason: {e}{reset}")
    exit(1)

print(f"{yellow}Cleaning build folder...{reset}")
for filename in os.listdir('build'):
    file_path = os.path.join('build', filename)
    try:
        if os.path.isfile(file_path) or os.path.islink(file_path):
            os.unlink(file_path)
        elif os.path.isdir(file_path):
            shutil.rmtree(file_path)
    except Exception as e:
        print(f"{red}Failed to delete {file_path}. Reason: {e}{reset}")
        exit(1)

try:
    print("Copying src_shell and src_manager folders into build folder...")
    shutil.copytree('src_shell', 'build/src_shell')
    shutil.copytree('src_manager', 'build/src_manager')
except Exception as e:
    print(f"{red}Failed to copy folders. Reason: {e}{reset}")
    exit(1)

try:
    print("Compressing contents of the shell folder into shell.tar.xz at maximum compression in the package folder...")
    with tarfile.open('build/shell.tar.xz', 'w:xz') as tar:
        for item in os.listdir('build/src_shell'):
            tar.add(os.path.join('build/src_shell', item), arcname=item)
except Exception as e:
    print(f"{red}Failed to compress shell folder. Reason: {e}{reset}")
    exit(1)

try:
    print("Encoding shell.tar.xz file in base64 and storing in a variable...")
    with open('build/shell.tar.xz', 'rb') as f:
        base64_shell_tar_xz = base64.b64encode(f.read()).decode()
except Exception as e:
    print(f"{red}Failed to encode shell.tar.xz file. Reason: {e}{reset}")
    exit(1)

if os.path.exists('build/src_manager/manager.php'):
    base64_shell_code = base64_shell_tar_xz
    php_file = "build/src_manager/manager.php"
    variable_to_change = 'shell_package'
    try:
        with open(php_file, 'r') as file:
            filedata = file.read()
        php_variable_format = "\$shell_package = \".*\";"
        replacement_format = f"$shell_package = \"{base64_shell_code}\";"
        filedata = re.sub(php_variable_format, replacement_format, filedata)
        if args.random:
            print("Giving the php file a random name...")
            random_name = os.urandom(16).hex()
            php_file = f"build/{random_name}.php"
        else:
            php_file = "build/manager.php"
        with open(php_file, 'w') as file:
            file.write(filedata)
    except Exception as e:
        print(f"{red}Failed to replace shell_package variable. Reason: {e}{reset}")
        exit(1)

try:
    print("Removing src_shell and src_manager folders from build folder...")
    shutil.rmtree('build/src_shell')
    shutil.rmtree('build/src_manager')
    os.remove('build/shell.tar.xz')
except Exception as e:
    print(f"{red}Failed to remove folders and file. Reason: {e}{reset}")
    exit(1)

print(f"{green}Done building!{reset}")

exit(0)