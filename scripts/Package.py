# DSJAS packager scripts
# This script is used to build the archives and other required distribution files
# Please see build instructions for more info

import os
import sys
import datetime
import shutil
import json

from time import sleep
from pathlib import *


def isInRoot():
    dirs = os.listdir(os.getcwd())

    return ".git" in dirs


def printWelcome():
    print("DSJAS packaging tool")
    print("Copyright 2020 - Ethan Marshall")
    print("This script will aid in allowing you to configure the project for packaging")
    print("\nPlease wait while we perform a few initial setup actions")


def generateDistName():
    timestamp = datetime.datetime.now()
    stringStamp = str(timestamp)

    stringStamp = stringStamp.replace(" ", "-")
    stringStamp = stringStamp.replace(":", "-")

    return stringStamp


def distExists():
    return os.path.isdir("dist")


def removeIfExists(path):
    if os.path.exists(path):
        os.remove(path)


def removeIfDirExists(dir):
    if os.path.exists(dir) and os.path.isdir(dir):
        shutil.rmtree(dir)


def createDist(distName):
    os.mkdir("dist")


def createDistSubs(distName):
    os.mkdir("dist/" + distName)

    os.makedirs("dist/" + distName + "/package")


def copySourceTree(distName):
    shutil.copytree("public", "dist/" + distName + "/src", False)


def cleanSourceTree(distName):
    os.chdir("dist/" + distName + "/src")

    removeIfExists("Version.json")

    removeIfExists("Config.ini")
    removeIfExists("admin/site/UI/config.ini")
    removeIfExists("admin/site/modules/config.ini")
    removeIfExists("admin/site/extensions/config.ini")

    removeIfExists("Debug.php")
    removeIfExists("setuptoken.txt")

    removeIfExists("admin/data/AdminNotices.json")

    removeIfDirExists("uploads")
    # The example modules should not be shipped with a release
    removeIfDirExists("admin/site/modules/example")
    # The test theme should not be snipped with a release
    removeIfDirExists("admin/site/UI/test_theme")

    os.chdir("../../..")
    if not isInRoot():
        print("[X] Failed to navigate from source directory")
        print("[!] Script cannot continue in this condition")
        quit(-1)


def copyDefaultConfiguration(distName):
    shutil.copyfile("scripts/install/Config.ini",
                    "dist/" + distName + "/src/Config.ini")
    shutil.copyfile("scripts/install/themeConfig.ini",
                    "dist/" + distName + "/src/admin/site/UI/config.ini")
    shutil.copyfile("scripts/install/moduleConfig.ini",
                    "dist/" + distName + "/src/admin/site/modules/config.ini")
    shutil.copyfile("scripts/install/moduleConfig.ini",
                    "dist/" + distName + "/src/admin/site/extensions/config.ini")

    shutil.copyfile("scripts/install/adminNotices.json", "dist/" +
                    distName + "/src/admin/data/AdminNotices.json")

    shutil.copyfile("scripts/install/ver.json", "dist/" +
                    distName + "/src/Version.json")


def updateVersionJSON(distName, majorVersion, minorVersion, patch, name, description, band):
    fileRead = open("dist/" + distName + "/src/Version.json", "r")

    jsonData = json.load(fileRead, strict=False)

    jsonData["version"]["major"] = majorVersion
    jsonData["version"]["minor"] = minorVersion
    jsonData["version"]["patch"] = patch
    jsonData["version-name"] = name
    jsonData["version-description"] = description
    jsonData["version-release-band"] = band

    fileRead.close()

    fileWrite = open("dist/" + distName + "/src/Version.json", "w")
    fileWrite.write(json.dumps(jsonData))
    fileWrite.close()


def createDummyVersion(distName):
    jsonData = {"version": {}, "version-name": "",
                "version-description": "", "version-release-band": ""}

    jsonData["version"]["major"] = "1"
    jsonData["version"]["minor"] = "0"
    jsonData["version"]["patch"] = "0"
    jsonData["version-name"] = ""
    jsonData["version-description"] = ""
    jsonData["version-release-band"] = "alpha"

    fileWrite = open("dist/" + distName + "/src/Version.json", "w")
    fileWrite.write(json.dumps(jsonData))
    fileWrite.close()


def createCompressedArchive(sourceDirectory, archiveName, archiveFormat):
    shutil.make_archive(archiveName, archiveFormat,
                        sourceDirectory)


def interpretBooleanInput(input):
    real = input.lower()

    if real == "y" or real == "1" or real == "yes":
        return True

    return False


def inputOrDefault(input, initial="", default=""):
    if input == initial or input == None:
        return default
    else:
        return input


def endHalt():
    print("\n\nThe packager script has completed successfully")
    print("Your package has been created in the directory with the current timestamp")
    input("Press enter to exit...")


def separator():
    print("=" * 50)


if __name__ == "__main__":
    # Check if we are in the git root (we need to be)
    if not isInRoot():
        print("[X] Please run this script from the root of the git repository")
        print(
            "[i] You can do this by calling the script with the scripts directory in the path")
        print("[i] For example: ./scripts/Package.py")
        quit(-1)

    # Print welcome
    printWelcome()

    # Create distribution directories
    print("[i] Creating distribution directory")

    if not distExists():
        createDist()

    distName = generateDistName()
    createDistSubs(distName)

    print("[OK] Success! The distribution " +
          distName + " has been initialized\n")

    separator()

    # Copy and clean the source tree
    print("[i] Copying source tree. This may take a while...")
    copySourceTree(distName)
    print("[OK] Success! Source tree copied\n")

    print("[i] Cleaning up and preparing to package...")
    cleanSourceTree(distName)
    print("[OK] Done! Ready for packaging...\n\n")

    separator()

    useDefaultConfigs = False

    versionMajor = ""
    versionMinor = ""
    patch = ""

    versionName = ""
    versionDesc = ""
    versionBand = ""

    print("[OK] Your package has been created")
    print("[i] However, we need information from you to compile the package")
    print("[i] Please enter that information below")
    print("[!] Default info is shown in parentheses and multiple choice are shown in square braces\n\n")

    separator()

    versionMajor = inputOrDefault(
        input("Major version number (1):"), initial='', default='1')
    versionMinor = inputOrDefault(
        input("Minor version number (0):"), initial='', default='0')
    patch = inputOrDefault(
        input("Major version number (0):"), initial='', default='0')

    separator()

    versionName = inputOrDefault(
        input("Version name:"), initial='', default='Minor update')
    versionDesc = inputOrDefault(
        input("Version description:"), initial='', default='Minor improvement update')
    versionBand = inputOrDefault(
        input("Version band [alpha/beta/stable]:"), initial='', default='alpha')

    separator()
    print("\n[i] Writing configuration. Please wait...")

    # Copy across the default config file
    copyDefaultConfiguration(distName)

    # Update version.json
    updateVersionJSON(distName, versionMajor, versionMinor,
                      patch, versionName, versionDesc, versionBand)

    print("[OK] Finished writing configuration")
    separator()

    print("[!] About to package archive")
    print("[?] Please confirm that the following details are accurate and that you are ready to package:\n")

    print("Default config present: " + str(useDefaultConfigs))
    print("Version ID: " + versionMajor + "." +
          versionMinor + "." + patch + "-" + versionBand)
    print("Version name: " + versionName)
    print("Version description: " + versionDesc)

    separator()

    ok = interpretBooleanInput(input("Do these details seem correct? [y/n]"))
    if ok:
        print("\n[!] Building archive. This will take a moment...")
        createCompressedArchive("dist/" + distName +
                                "/src/", "dist/" + distName + "/package/DSJAS-release-" + versionBand, "zip")
    else:
        print("[!] Operation aborted")
        print("[X] The user refused to confirm details")
        quit(-1)

    print("[OK] Success! Packaging has completed. Your archive has been built in the package folder")

    # Goodbye
    endHalt()
