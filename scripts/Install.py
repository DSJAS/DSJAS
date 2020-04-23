# DSJAS development install script
# Run this script to have the necessary configuration and other files added to your install
# The site will not function unless this file is run first

import os
import sys

from time import sleep
from pathlib import *

requiredFiles = ["Config.ini", "admin/site/UI/config.ini",
                 "admin/site/modules/config.ini", "Version.json",
                 "admin/site/extensions/config.ini", "admin/data/AdminNotices.json", ]

defaultContentNames = ["Config.ini", "themeConfig.ini",
                       "moduleConfig.ini", "ver.json", "extConfig.ini", "adminNotices.json"]
defaultContent = []
defaultContentDirectory = "install/"


def isInstalled():
    for i in range(0, len(requiredFiles)):
        path = "./" + requiredFiles[i]
        if not os.path.exists(path):
            return False

    return True


def changeToPublicDirectory():
    path = os.getcwd()

    if os.path.basename(path) == "public":
        return True
    elif os.path.basename(path) == "scripts":
        os.chdir("../public")
        return True

    else:
        dirs = os.listdir()

        if dirs.__contains__("public"):
            os.chdir("public")
            return True
        else:
            return False


def changeToScripts():
    if os.path.basename(os.getcwd()) == "scripts":
        return True

    try:
        os.chdir("scripts")
        return True
    except FileNotFoundError:
        return False


def loadDefaultContent():
    changeToScripts()

    global defaultContent
    defaultContent = [None] * len(defaultContentNames)

    sys.stdout.write("\nPreparing to install [")

    for i in range(0, len(defaultContentNames)):
        sys.stdout.write("=")
        sys.stdout.flush()

        try:
            file = open(defaultContentDirectory + defaultContentNames[i], "r")
            defaultContent[i] = file.read()

            file.close()
        except FileNotFoundError:
            print("\n\nE: A file required for installation was not found")
            print("Please ensure that the file " +
                  defaultContentNames[i] + " is present in the required location")

            quit(-1)

        sleep(0.1)

    sys.stdout.write("]\n")


def createRequiredFile(filepath, content):
    realpath = Path(filepath)
    dirpath = Path(realpath.parent)

    if not os.path.isfile(realpath.parent):
        dirpath.mkdir(parents=True, exist_ok=True)

    file = open(filepath, "w+")
    file.write(content)
    file.close()


if __name__ == "__main__":
    print("Installing DSJAS. Please wait...")

    loadDefaultContent()

    if not changeToPublicDirectory():  # Change the public directory to public to create files required
        print("E: Could not locate the public directory")
        print("Please ensure that this script is being run from either the scripts directory or the git root")

    if isInstalled():  # Confirm reinstallation
        print("W: DSJAS is already installed")
        print("Running this script again will reset the configuration to default")

        choice = input("Are you sure you wish to continue? [y/n]")

        if (choice.lower() == "n"):
            print("Install aborted. Exiting...")
            quit(-1)

    sys.stdout.write("\nInstalling [")

    for i in range(0, len(requiredFiles)):
        sys.stdout.write("=")
        sys.stdout.flush()
        createRequiredFile(requiredFiles[i], defaultContent[i])

        sleep(0.1)

    sys.stdout.write("]")
    print("\n\nInstallation succeeded! DSJAS is now configured for the web installer")
