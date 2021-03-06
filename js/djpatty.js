jQuery(document).ready(function(){

    var requestCallback = new MyRequestsCompleted({
        numRequest: jQuery('.album').length,
        singleCallback: function(){
            jQuery('#loading').remove();
            addScrollingImages();
        }
    });
    

    	for(var i = 0; i < jQuery('.album').length; i++) {

            new Ajax.Request("loadAlbum.php", { 
                method: 'GET', 
                parameters: {
                    artisturi: jQuery('.artist').attr('id'),
                    albumuri: jQuery(jQuery('.album')[i]).attr('id'),
                    artist: jQuery('.artist').attr('data-name'),
                    album: jQuery(jQuery('.album')[i]).attr('data-name')
                },
                onSuccess: function(req){
                    var response = req.responseText.split('@');
                    console.log(response[0]);
                    var div = document.getElementById(response[0]);
                    div.innerHTML = response[1];
                    addClickListener(jQuery(div));
                    requestCallback.requestComplete(true);
                }, 
                onFailure: function(req){
                    jQuery('.artist').innerHTML = "<p>Album could not be loaded</p>";
                }
            });
        }

        if(jQuery('.album').length < 1 && jQuery('#loading')){
            var div = jQuery('#loading');
            if(div[0] != undefined)
                div[0].innerHTML = "<h3>This artist has no albums</h3>";
        }

    for(var i = 0; i < jQuery('.recommendEvents').length; i++){
        new Ajax.Request("loadEvents.php", {
            method: 'GET',
            parameters: {
                userid: jQuery('.recommendEvents').attr('id')
            },
            onSuccess: function(req){
                jQuery('.recommendEvents')[0].innerHTML = req.responseText;
            },
            onFailure: function(req){
                jQuery('.recommendEvents').innerHTML[0] = "<p>Event recommendations could not be loaded</p>";
            }
        });
    }

})

function addScrollingImages(){
    for(var i = 0; i < jQuery('.albumArt').length; i++) {
        if(!jQuery('.album')[i+1]){
            var lim = jQuery(jQuery('.album')[i]).offset().top + jQuery(jQuery('.album')[i]).height() - jQuery(jQuery('.albumArt')[i]).height();
        }
        else{
            var lim = jQuery(jQuery('.album')[i+1]).offset().top - jQuery(jQuery('.albumArt')[i]).height() - 10;
        }
        jQuery(jQuery('.albumArt')[i]).scrollToFixed({
                marginTop: 10,
                limit: lim
        });
    }
}

function addClickListener(container){
    container.find('.track').click(function(){
        addPlay(jQuery(this));
        setSpotifyPlayer(jQuery(this).attr('data-album'),jQuery(this).attr('id'));
    })
}

function addPlay(container){
    new Ajax.Request("addPlay.php", { 
        method: 'GET', 
        parameters: {
            artisturi: container.attr('data-artist-uri'),
            artist: container.attr('data-artist')
        }
    });
}

function setSpotifyPlayer(divId, trackId){
    var res = divId;
	document.getElementById(res).innerHTML = '<iframe class="spotifybutton" src="https://embed.spotify.com/?uri=' + trackId + '" width="300" height="380" frameborder="0" allowtransparency="true"></iframe>';
}

var MyRequestsCompleted = (function() {
    var numRequestToComplete, requestsCompleted, callBacks, singleCallBack;

    return function(options) {
        if (!options) options = {};

        numRequestToComplete = options.numRequest || 0;
        requestsCompleted = options.requestsCompleted || 0;
        callBacks = [];
        var fireCallbacks = function() {
            for (var i = 0; i < callBacks.length; i++) callBacks[i]();
        };
        if (options.singleCallback) callBacks.push(options.singleCallback);

        this.addCallbackToQueue = function(isComplete, callback) {
            if (isComplete) requestsCompleted++;
            if (callback) callBacks.push(callback);
            if (requestsCompleted == numRequestToComplete) fireCallbacks();
        };
        this.requestComplete = function(isComplete) {
            if (isComplete) requestsCompleted++;
            if (requestsCompleted == numRequestToComplete) fireCallbacks();
        };
        this.setCallback = function(callback) {
            callBacks.push(callBack);
        };
    };
})();

(function(jQuery) {
    jQuery.isScrollToFixed = function(el) {
        return jQuery(el).data('ScrollToFixed') !== undefined;
    };

    jQuery.ScrollToFixed = function(el, options) {
        // To avoid scope issues, use 'base' instead of 'this' to reference this
        // class from internal events and functions.
        var base = this;

        // Access to jQuery and DOM versions of element.
        base.jQueryel = jQuery(el);
        base.el = el;

        // Add a reverse reference to the DOM object.
        base.jQueryel.data('ScrollToFixed', base);

        // A flag so we know if the scroll has been reset.
        var isReset = false;

        // The element that was given to us to fix if scrolled above the top of
        // the page.
        var target = base.jQueryel;

        var position;

        // The offset top of the element when resetScroll was called. This is
        // used to determine if we have scrolled past the top of the element.
        var offsetTop = 0;

        // The offset left of the element when resetScroll was called. This is
        // used to move the element left or right relative to the horizontal
        // scroll.
        var offsetLeft = 0;
        var originalOffsetLeft = -1;

        // This last offset used to move the element horizontally. This is used
        // to determine if we need to move the element because we would not want
        // to do that for no reason.
        var lastOffsetLeft = -1;

        // This is the element used to fill the void left by the target element
        // when it goes fixed; otherwise, everything below it moves up the page.
        var spacer = null;

        var spacerClass;

        // Capture the original offsets for the target element. This needs to be
        // called whenever the page size changes or when the page is first
        // scrolled. For some reason, calling this before the page is first
        // scrolled causes the element to become fixed too late.
        function resetScroll() {
            // Set the element to it original positioning.
            target.trigger('preUnfixed');
            setUnfixed();
            target.trigger('unfixed');

            // Reset the last offset used to determine if the page has moved
            // horizontally.
            lastOffsetLeft = -1;

            // Capture the offset top of the target element.
            offsetTop = target.offset().top;

            // Capture the offset left of the target element.
            offsetLeft = target.offset().left;
            
            // If the offsets option is on, alter the left offset.
            if (base.options.offsets) {
                offsetLeft += (target.offset().left - target.position().left);
            }
            
            if (originalOffsetLeft == -1) {
                originalOffsetLeft = offsetLeft;
            }

            position = target.css('position');

            // Set that this has been called at least once.
            isReset = true;
            
            if (base.options.bottom != -1) {
                target.trigger('preFixed');
                setFixed();
                target.trigger('fixed');
            }
        }

        function getLimit() {
            var limit = base.options.limit;
            if (!limit) return 0;

            if (typeof(limit) === 'function') {
                return limit();
            }
            return limit;
        }

        // Returns whether the target element is fixed or not.
        function isFixed() {
            return position === 'fixed';
        }

        // Returns whether the target element is absolute or not.
        function isAbsolute() {
            return position === 'absolute';
        }

        function isUnfixed() {
            return !(isFixed() || isAbsolute());
        }

        // Sets the target element to fixed. Also, sets the spacer to fill the
        // void left by the target element.
        function setFixed() {
            // Only fix the target element and the spacer if we need to.
            if (!isFixed()) {
                // Set the spacer to fill the height and width of the target
                // element, then display it.
                spacer.css({
                    'display' : target.css('display'),
                    'width' : target.outerWidth(true),
                    'height' : target.outerHeight(true),
                    'float' : target.css('float')
                });

                // Set the target element to fixed and set its width so it does
                // not fill the rest of the page horizontally. Also, set its top
                // to the margin top specified in the options.
                target.css({
                    'width' : target.width(),
                    'position' : 'fixed',
                    'top' : base.options.bottom == -1?getMarginTop():'',
                    'bottom' : base.options.bottom == -1?'':base.options.bottom
                });

                position = 'fixed';
            }
        }

        function setAbsolute() {
            target.css({
                'width' : target.width(),
                'position' : 'absolute',
                'top' : getLimit(),
                'left' : offsetLeft
            });

            position = 'absolute';
        }

        // Sets the target element back to unfixed. Also, hides the spacer.
        function setUnfixed() {
            // Only unfix the target element and the spacer if we need to.
            if (!isUnfixed()) {
                lastOffsetLeft = -1;

                // Hide the spacer now that the target element will fill the
                // space.
                spacer.css('display', 'none');

                // Remove the style attributes that were added to the target.
                // This will reverse the target back to the its original style.
                target.css({
                    'width' : '',
                    'position' : '',
                    'left' : '',
                    'top' : ''
                });

                position = null;
            }
        }

        // Moves the target element left or right relative to the horizontal
        // scroll position.
        function setLeft(x) {
            // Only if the scroll is not what it was last time we did this.
            if (x != lastOffsetLeft) {
                // Move the target element horizontally relative to its original
                // horizontal position.
                target.css('left', offsetLeft - x);

                // Hold the last horizontal position set.
                lastOffsetLeft = x;
            }
        }

        function getMarginTop() {
            return base.options.marginTop;
        }

        // Checks to see if we need to do something based on new scroll position
        // of the page.
        function checkScroll() {
            if (!jQuery.isScrollToFixed(target)) return;
            var wasReset = isReset;

            // If resetScroll has not yet been called, call it. This only
            // happens once.
            if (!isReset) {
                resetScroll();
            }

            // Grab the current horizontal scroll position.
            var x = jQuery(window).scrollLeft();

            // Grab the current vertical scroll position.
            var y = jQuery(window).scrollTop();

            // Get the limit, if there is one.
            var limit = getLimit();

            // If the vertical scroll position, plus the optional margin, would
            // put the target element at the specified limit, set the target
            // element to absolute.
            if (base.options.minWidth && jQuery(window).width() < base.options.minWidth) {
                if (!isUnfixed() || !wasReset) {
                    postPosition();
                    target.trigger('preUnfixed');
                    setUnfixed();
                    target.trigger('unfixed');
                }
            } else if (base.options.bottom == -1) {
                // If the vertical scroll position, plus the optional margin, would
                // put the target element at the specified limit, set the target
                // element to absolute.
                if (limit > 0 && y >= limit - getMarginTop()) {
                    if (!isAbsolute() || !wasReset) {
                        postPosition();
                        target.trigger('preAbsolute');
                        setAbsolute();
                        target.trigger('unfixed');
                    }
                // If the vertical scroll position, plus the optional margin, would
                // put the target element above the top of the page, set the target
                // element to fixed.
                } else if (y >= offsetTop - getMarginTop()) {
                    if (!isFixed() || !wasReset) {
                        postPosition();
                        target.trigger('preFixed');

                        // Set the target element to fixed.
                        setFixed();

                        // Reset the last offset left because we just went fixed.
                        lastOffsetLeft = -1;

                        target.trigger('fixed');
                    }
                    // If the page has been scrolled horizontally as well, move the
                    // target element accordingly.
                    setLeft(x);
                } else {
                    // Set the target element to unfixed, placing it where it was
                    // before.
                    if (!isUnfixed() || !wasReset) {
                        postPosition();
                        target.trigger('preUnfixed');
                        setUnfixed();
                        target.trigger('unfixed');
                    }
                }
            } else {
                if (limit > 0) {
                    if (y + jQuery(window).height() - target.outerHeight(true) >= limit - getMarginTop()) {
                        if (isFixed()) {
                            postPosition();
                            target.trigger('preUnfixed');
                            setUnfixed();
                            target.trigger('unfixed');
                        }
                    } else {
                        if (!isFixed()) {
                            postPosition();
                            target.trigger('preFixed');
                            setFixed();
                        }
                        setLeft(x);
                        target.trigger('fixed');
                    }
                } else {
                    setLeft(x);
                }
            }
        }

        function postPosition() {
            var position = target.css('position');
            
            if (position == 'absolute') {
                target.trigger('postAbsolute');
            } else if (position == 'fixed') {
                target.trigger('postFixed');
            } else {
                target.trigger('postUnfixed');
            }
        }

        var windowResize = function(event) {
            // Check if the element is visible before updating it's position, which
            // improves behavior with responsive designs where this element is hidden.
            if(target.is(':visible')) {
                isReset = false;
                checkScroll();
			}
        }

        var windowScroll = function(event) {
            checkScroll();
        }

        // Initializes this plugin. Captures the options passed in, turns this
        // off for iOS, adds the spacer, and binds to the window scroll and
        // resize events.
        base.init = function() {
            // Capture the options for this plugin.
            base.options = jQuery
                    .extend({}, jQuery.ScrollToFixed.defaultOptions, options);

            // Turn off this functionality for iOS devices until we figure out
            // what to do with them, or until iOS5 comes out which is supposed
            // to support position:fixed.
            if (navigator.platform === 'iPad' || navigator.platform === 'iPhone' || navigator.platform === 'iPod') {
                if (!navigator.userAgent.match(/OS 5_.*\ like Mac OS X/i)) {
                    return;
                }
            }

            // Put the target element on top of everything that could be below
            // it. This reduces flicker when the target element is transitioning
            // to fixed.
            base.jQueryel.css('z-index', base.options.zIndex);

            // Create a spacer element to fill the void left by the target
            // element when it goes fixed.
            spacer = jQuery('<div />');

            position = target.css('position');

            // Place the spacer right after the target element.
            if (isUnfixed()) base.jQueryel.after(spacer);

            // Reset the target element offsets when the window is resized, then
            // check to see if we need to fix or unfix the target element.
            jQuery(window).bind('resize.ScrollToFixed', windowResize);

            // When the window scrolls, check to see if we need to fix or unfix
            // the target element.
            jQuery(window).bind('scroll.ScrollToFixed', windowScroll);
            
            if (base.options.preFixed) {
                target.bind('preFixed.ScrollToFixed', base.options.preFixed);
            }
            if (base.options.postFixed) {
                target.bind('postFixed.ScrollToFixed', base.options.postFixed);
            }
            if (base.options.preUnfixed) {
                target.bind('preUnfixed.ScrollToFixed', base.options.preUnfixed);
            }
            if (base.options.postUnfixed) {
                target.bind('postUnfixed.ScrollToFixed', base.options.postUnfixed);
            }
            if (base.options.preAbsolute) {
                target.bind('preAbsolute.ScrollToFixed', base.options.preAbsolute);
            }
            if (base.options.postAbsolute) {
                target.bind('postAbsolute.ScrollToFixed', base.options.postAbsolute);
            }
            if (base.options.fixed) {
                target.bind('fixed.ScrollToFixed', base.options.fixed);
            }
            if (base.options.unfixed) {
                target.bind('unfixed.ScrollToFixed', base.options.unfixed);
            }

            if (base.options.spacerClass) {
                spacer.addClass(base.options.spacerClass);
            }

            target.bind('resize.ScrollToFixed', function() {
                spacer.height(target.height());
            });

            target.bind('scroll.ScrollToFixed', function() {
                target.trigger('preUnfixed');
                setUnfixed();
                target.trigger('unfixed');
                checkScroll();
            });

            target.bind('remove.ScrollToFixed', function() {
                target.trigger('preUnfixed');
                setUnfixed();
                target.trigger('unfixed');

                jQuery(window).unbind('resize.ScrollToFixed', windowResize);
                jQuery(window).unbind('scroll.ScrollToFixed', windowScroll);

                target.unbind('.ScrollToFixed');
                base.jQueryel.removeData('ScrollToFixed');
            });
            
            // Reset everything.
            windowResize();
        };

        // Initialize the plugin.
        base.init();
    };

    // Sets the option defaults.
    jQuery.ScrollToFixed.defaultOptions = {
        marginTop : 0,
        limit : 0,
        bottom : -1,
        zIndex : 1000
    };

    // Returns enhanced elements that will fix to the top of the page when the
    // page is scrolled.
    jQuery.fn.scrollToFixed = function(options) {
        return this.each(function() {
            (new jQuery.ScrollToFixed(this, options));
        });
    };
})(jQuery);