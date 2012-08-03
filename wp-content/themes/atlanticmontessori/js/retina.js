(function() {

var root = (typeof exports == 'undefined' ? window : exports);

function RetinaImagePath(path) {
  this.path = path;
  // Split the file extension off the image path,
  // and put it back together with @2x before the extension.
  // "/path/to/image.png" => "/path/to/image@2x.png"
  var path_segments           = this.path.split('.');
  var path_without_extension  = path_segments.slice(0, (path_segments.length - 1)).join(".");
  var extension               = path_segments[path_segments.length - 1];
  this.at_2x_path             = path_without_extension + "@2x." + extension;
}
    
root.RetinaImagePath = RetinaImagePath;
  
// Class variable [Array] 
// cache of files we've checked on the server
RetinaImagePath.confirmed_paths = [];
  
// Function to test if image is external
RetinaImagePath.prototype.is_external = function() {
  return !!(this.path.match(/^https?\:/i) && !this.path.match('//' + document.domain) )
}

RetinaImagePath.prototype.check_2x_variant = function(callback) {
  var http, that = this;
  if (this.is_external()) {
    // If the image path is on an external server,
    // exit early to avoid cross-domain ajax errors
    return callback(false);
  } else if (this.at_2x_path in RetinaImagePath.confirmed_paths) {
    // If we have already checked and confirmed that
    // the @2x variant exists, then just return true
    return callback(true);
  } else {
    // Otherwise, prepare an AJAX request for the HEAD only.
    // We don't need a full request because we're only
    // checking to see if the @2x version exists on the server
    http = new XMLHttpRequest;
    http.open('HEAD', this.at_2x_path);
    http.onreadystatechange = function() {
      if (http.readyState != 4) {
        return callback(false);
      }

      // If we get an A-OK from the server,
      // push file path onto array of confirmed files
      if (http.status >= 200 && http.status <= 399) {
        RetinaImagePath.confirmed_paths.push(that.at_2x_path);
        return callback(true);
      } else {
        return callback(false);
      }
    }
    http.send();
  }
}

function RetinaImage(el) {
  this.el = el;
  this.path = new RetinaImagePath(this.el.getAttribute('src'));
  var that = this;
  this.path.check_2x_variant(function(hasVariant) {
    if (hasVariant) that.swap();
  });
}

root.RetinaImage = RetinaImage;

// Method for swapping in a new image path
// Applies the dimensions of the existing image
// to the image with the new image path
RetinaImage.prototype.swap = function(path) {
  if (typeof path == 'undefined') path = this.path.at_2x_path;

  // We wrap this in a named self-executer so we can reference 
  // it in a setTimeout if the image has not loaded yet.
  var that = this;
  function load() {
    if (! that.el.complete) {
      // Check that the image has loaded.
      // We need to wait for the image to load to grab proper dimensions.

      // If it has not, try again in a few milliseconds.
      // We've found 5ms to be the fastest we can crank this up
      // and still have the script detect image loads reliably and efficiently.
      setTimeout(load, 5);
    } else {
      // Once the the image has loaded we know we 
      // can grab the proper dimensions of the original image
      // and go ahead and swap in the new image path and apply the dimensions
      that.el.setAttribute('width', that.el.offsetWidth);
      that.el.setAttribute('height', that.el.offsetHeight);
      that.el.setAttribute('src', path);
    }
  }
  load();
}

// First check that we're on a retina device.
// We don't even want this guy executing if we're not.
// Bind everything to the window object's onload.
// This helps wait for the images to get loaded into the DOM.

if (root.devicePixelRatio > 1) {
  window.onload = function() {
    var images = document.getElementsByTagName("img"), retinaImages = [], i, image;
    for (i = 0; i < images.length; i++) {
      image = images[i];
      retinaImages.push(new RetinaImage(image));
    }
  }
}

})();
