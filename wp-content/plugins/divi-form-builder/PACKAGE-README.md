# Packaging this plugin via the CLI

Before proceding with the below steps, you must ensure you have ran `npm install` - you will need node version 14.0.0

Once you have done `npm install` you will also need to run `npm install -g grunt-cli`. Note: You will only need to do this once on your system as it installs globally.

## Steps to follow before packaging on each release
- Update version number in the following places
- - package.json
- - main plugin file
- - changelog
- Add release date to changelog
- Copy unminified JS files from /js folder to REMOVE_WHEN_UPDATE folder
- Minify JS and CSS files in /js and /css folders

## Packaging process
- Run `yarn build` - This will run the build script from the Divi Scripts node module but will _not_ package in a zip file
- Run `grunt` - This takes the built plugin and packages it in a zip file. It will exclude the relevant files, these can be seen in `Gruntfile.js`. It will also set the zip filename to the `[plugin-name].[version].zip` (as defined in package.json) 

The zip file is now ready to be uploaded to the site via FTP and WooCommerce to be updated. 

*The expected package time for the `grunt` command is 2m30s*