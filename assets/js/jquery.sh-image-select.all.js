/*! jQuery SH Image Select Drop-down v1.1.3, Copyright 2012 Alexy Vauch! */
(function($) {
    /**
     * Creates a drop-down list of images for all matched elements.
     *
     * @example $("#select").shImageSelectDropdown();
     * @input "data-image" attribute with image link value required. Ex: <option data-image="image.jpg">image</option>
     *
     * @method shImageSelectDropdown
     * @return $
     * @param o {Object} A set of configuration properties (key/value pairs).
     */
    $.fn.shImageSelectDropdown = function (o){
        var defaults = {
                theme: 'arrows', // Theme name
                expandDirection: 'down', // The direction of the image list expanding
                dropdownEffect: { // Allowed values: "fade", "horizontal-expand", "vertical-expand", "expand".
                    open: '',
                    close: ''
                },
                easing: {  // A string indicating which easing function to use for the transition.
                    open: '',
                    close: ''
                },
                showText: true, // Show select option text over image? // Allowed values: true, false.
                animationSpeed: {
                    open: 0,  // Animation speed on opening drop-down list (in milliseconds).
                    close: 0 // Animation speed on closing drop-down list (in milliseconds).
                },
                imageLimit: 3, // Limits the maximum number of visible images. "0" - Unlim.
                additionalClass: '', // Name of additional plugin html container css class.
                skin: 'light', // Skin name. Allowed values: "light", "dark"
                callbacks: { // Callback functions
                    onOpen: '',
                    onClose: '',
                    onSelect: '',
                    onImagesReady: ''
                }
            },
            options;

        options = $.extend(true, defaults, o);

        return this.each(function() {
            $(this).data('sh-image-select-dropdown', new shImageSelectDropdown($(this), options));
        });
    };

    /**
     * @constructor
     * @class shImageSelectDropdown
     * @param element {HTMLElement} The element to create the image drop-down for.
     * @param options {Object} A set of configuration properties (key/value pairs).
     */
    var shImageSelectDropdown = function (element, options){
        var self = this;

        this.container = $('<div></div>').addClass('sh-img-dropdown-container');
        this.selectBox = $('<div></div>').addClass('sh-img-dropdown-box');
        this.selectBoxOverlay = $('<div></div>').addClass('sh-img-dropdown-box-overlay');
        this.underOverlayLayer = $('<div></div>').addClass('sh-img-dropdown-under-overlay');
        this.list = $('<div></div>').addClass('sh-img-dropdown-list');
        this.listContainer = $('<div></div>').addClass('sh-img-dropdown-list-container');
        this.listScreen = $('<div></div>').addClass('sh-img-dropdown-list-screen');

        /**
         * Opens image list.
         *
         * @method open
         * @return undefined
         */
        this.open = function (){
            animateDropdown(effects.currentEffect.open, options.animationSpeed.open, easingEffect.onOpen, options.callbacks.onOpen);
        };

        /**
         * Closes image list.
         *
         * @method close
         * @return undefined
         */
        this.close = function (){
            animateDropdown(effects.currentEffect.close, options.animationSpeed.close, easingEffect.onClose, options.callbacks.onClose);
        };

        /**
         * Selects image.
         *
         * @method select
         * @return undefined
         */
        this.select = function (image){
            if (typeof image !== 'object') {
                image = self.list.find('.sh-img-dropdown-image-' + image);
            }

            selectImage(image);
        };

        var eventHandlers = function (scrollbar){
                var showImageText = function (imageTextBlock){
                        imageTextBlock.stop().animate({
                            bottom: '5px'
                        }, 'fast');
                    },

                    hideImageText = function (imageTextBlock){
                        imageTextBlock.stop().animate({
                            bottom: '-100%'
                        }, 'fast');
                    },

                    lightOffImage = function (image){
                        image.stop().fadeTo(300, 0.6);
                    },

                    lightOnImage = function (image){
                        image.stop().fadeTo(300, 1);
                    },

                    doImageNonSelectActionsTouch,
                    undoImageNonSelectActionsTouch,
                    doImageNonSelectActionsMouse,
                    undoImageNonSelectActionsMouse;

                if(options.showText) {
                    doImageNonSelectActionsMouse = function (imageContainer){
                        lightOffImage(imageContainer.children('img'));
                        showImageText(imageContainer.children('.sh-img-dropdown-textblock'));
                    };

                    undoImageNonSelectActionsMouse = function (imageContainer){
                        lightOnImage(imageContainer.children('img'));
                        hideImageText(imageContainer.children('.sh-img-dropdown-textblock'));
                    };

                    doImageNonSelectActionsTouch = doImageNonSelectActionsMouse;
                    undoImageNonSelectActionsTouch = undoImageNonSelectActionsMouse;

                } else {
                    doImageNonSelectActionsMouse = function (imageContainer){
                        lightOffImage(imageContainer.children('img'));
                    };

                    undoImageNonSelectActionsMouse = function (imageContainer){
                        lightOnImage(imageContainer.children('img'));
                    };

                    doImageNonSelectActionsTouch = function (imageContainer){};
                    undoImageNonSelectActionsTouch = doImageNonSelectActionsTouch;
                }

                eventHandlers = {
                    mouse: {
                        doMouseClickImageActions: function (e){
                            e.preventDefault();

                            selectImage($(this).children('img').css('opacity', 1));
                            self.close();
                        },

                        doMouseOverImageActions: function (){
                            doImageNonSelectActionsMouse($(this));

                        },

                        doMouseOutImageActions: function (){
                            undoImageNonSelectActionsMouse($(this));

                        }
                    },

                    touch: (function (){
                        var fingerOn = false,
                            imageTextIsVisible = false;

                        return {
                            doTouchStartImageActions: function (e){
                                var imageContainer = $(this),
                                    image = imageContainer.children('img');

                                e.preventDefault();

                                fingerOn = true;

                                setTimeout(function (){
                                    var draggingInEventsCycleFlag = scrollbar.draggingInEventsCycleFlag();

                                    if (!draggingInEventsCycleFlag) {
                                        if (fingerOn) {
                                            doImageNonSelectActionsTouch(imageContainer);
                                            imageTextIsVisible = true;

                                        } else {
                                            selectImage(image.css('opacity', 1));
                                            self.close();
                                        }
                                    }
                                }, 200)
                            },

                            doTouchEndImageActions: function (){
                                fingerOn = false;

                                if (imageTextIsVisible) {
                                    undoImageNonSelectActionsTouch($(this));
                                    imageTextIsVisible = false;
                                }
                            }
                        }
                    })(),

                    toggleDropDownListVisibility: function (){
                        self.listContainer.is(':hidden') ? self.open() : self.close();
                    }
                }
            },

            animateDropdown = function (effect, speed, easing, callback){
                self.listContainer.stop().animate(effect, speed, easing, function (){
                    $.shHelper.callCallback(callback, self);
                });
            },

            selectImage = function (image){
                element.children().removeAttr('selected')
                    .filter('.' + image.attr('class')).attr('selected', 'selected');

                element.triggerHandler('change');

                setSelectedImage(image, options.callbacks.onSelect);
            },

            setSelectedImage = function (newImage, callback){
                self.selectBox.empty().prepend(newImage.clone());

                if(callback) {
                    $.shHelper.callCallback(callback, newImage, self);
                }
            },

            imageListExpansion = (function (){
                var expandDirection = options.expandDirection,
                    cssClass = 'sh-img-dropdown-direction-',
                    effect = 'vertical-expand',
                    scrollbarAxis = 'y',
                    expandSize = 'height',
                    extraWidth = false,
                    containerOffset = {};

                switch (options.expandDirection) {
                    case 'down':
                        cssClass += expandDirection;
                        extraWidth = true;
                        break;
                    case 'up':
                        cssClass += expandDirection;
                        extraWidth = true;
                        containerOffset = {
                            open: {
                                'margin-top': '-100%'
                            },
                            close: {
                                'margin-top': '0'
                            }
                        };
                        break;
                    case 'right':
                        cssClass += expandDirection;
                        expandSize = 'width';
                        scrollbarAxis = 'x';
                        effect = 'horizontal-expand';
                        break;
                    case 'left':
                        cssClass += expandDirection;
                        expandSize = 'width';
                        scrollbarAxis = 'x';
                        containerOffset = {
                            open: {
                                'margin-left': '-100%'
                            },
                            close: {
                                'margin-left': '0'
                            }
                        };

                        effect = 'horizontal-expand';
                        break;
                    case 'left-right':
                        cssClass += expandDirection;
                        expandSize = 'width';
                        scrollbarAxis = 'x';
                        containerOffset = {
                            open: {
                                'margin-left': '-50%'
                            },
                            close: {
                                'margin-left': '0'
                            }
                        };

                        effect = 'horizontal-expand';
                        break;
                    case 'up-down':
                        cssClass += expandDirection;
                        extraWidth = true;
                        containerOffset = {
                            open: {
                                'margin-top': '-50%'
                            },
                            close: {
                                'margin-top': '0'
                            }
                        };
                        break;
                    default:
                        cssClass += expandDirection;
                        extraWidth = true;
                }

                return {
                    cssClass: cssClass,
                    effect: effect,
                    scrollbarAxis: scrollbarAxis,
                    expandSize: expandSize,
                    extraWidth: extraWidth,
                    containerOffset: containerOffset
                };
            })(),

            theme = (function (){
                var themeName = options.theme,
                    cssClass = 'sh-img-dropdown-theme-',
                    events = {},
                    elements = [];

                switch (themeName) {
                    case 'minimal':
                        cssClass += themeName;
                        events = {
                            mouseover: {
                                element: self.selectBoxOverlay,
                                handler: function (){
                                    self.underOverlayLayer.stop().fadeTo(300, 1);
                                }
                            },

                            mouseout: {
                                element: self.selectBoxOverlay,
                                handler: function (){
                                    self.underOverlayLayer.stop().fadeOut();
                                }
                            }
                        };

                        elements.push({
                            container: self.container,
                            element: $('<div></div>').addClass(cssClass + '-arrow')
                        });
                        break;
                    case 'arrows':
                        cssClass += themeName;
                        break;
                    default:
                        cssClass += themeName;
                }

                return {
                    cssClass: cssClass,
                    events: events,
                    elements: elements
                };
            })(),

            skin = (function (){
                var skinName = options.skin,
                    cssClass = 'sh-img-dropdown-',
                    scrollbarSkin = 'light';

                switch (skinName) {
                    case 'dark':
                        cssClass += skinName;
                        scrollbarSkin = 'dark';
                        break;
                    case 'light':
                        cssClass += skinName;
                        break;
                    default:
                        cssClass += skinName;
                }

                return {
                    cssClass: cssClass,
                    scrollbarSkin: scrollbarSkin
                };
            })(),

            effects = (function (){
                var animationEffects = {
                    expand: {
                        open: {
                            height: 'show',
                            width: 'show'
                        },
                        close: {
                            height: 'hide',
                            width: 'hide'
                        }
                    },
                    'vertical-expand': {
                        open: {
                            height: 'show'
                        },
                        close: {
                            height: 'hide'
                        }
                    },
                    'horizontal-expand': {
                        open: {
                            width: 'show'
                        },
                        close: {
                            width: 'hide'
                        }
                    },
                    fade: {
                        open: {
                            opacity: 'show'
                        },
                        close: {
                            opacity: 'hide'
                        }
                    }
                };

                return {
                    currentEffect: {},

                    effectExists: function (effectName){
                        return (typeof effectName === 'string' && effectName in animationEffects);
                    },

                    getDefaultEffectProps: function (){
                        return animationEffects['vertical-expand'];
                    },

                    getEffectProps: function (effectName){
                        var currentEffectProps = false;

                        if (this.effectExists(effectName)) {
                            currentEffectProps = animationEffects[effectName];
                        }

                        return currentEffectProps;
                    },

                    getEffectPropsFromObj: function (effectObj){
                        var methods = this,
                            readyEffect = {};

                        $.each(effectObj, function (event, effectProp){
                            if (typeof effectProp === 'object') {
                                readyEffect[event] = effectProp;

                            } else if(methods.effectExists(effectProp)) {
                                readyEffect[event] = methods.getEffectProps(effectProp)[event];
                            }
                        });

                        return readyEffect;
                    },

                    pushEffect: function (effect){
                        var readyEffect = {},
                            effectProps;

                        if (typeof effect === 'string') {
                            if (this.effectExists(effect)) {
                                effectProps = this.getEffectProps(effect);

                                readyEffect['open'] = effectProps.open;
                                readyEffect['close'] = effectProps.close;
                            }

                        } else if (typeof effect === 'object') {
                            readyEffect = this.getEffectPropsFromObj(effect);
                        }

                        $.extend(this.currentEffect, readyEffect);

                    }
                };
            })(),

            easingEffect = (function () {
                var easingName = options.easing,
                    onOpen,
                    onClose;

                if (typeof easingName === 'string') {
                    onOpen = onClose = easingName;

                } else if (typeof easingName === 'object') {
                    if ('open' in easingName) {
                        onOpen = easingName.open;
                    }

                    if ('close' in easingName) {
                        onClose = easingName.close;
                    }
                }

                return {
                    onOpen: onOpen,
                    onClose: onClose
                }
            })(),

            addElementsToDom = function (){
                self.list.append(self.listScreen);
                self.listContainer.append(self.list);
                self.container.append(self.listContainer, self.selectBox, self.underOverlayLayer, self.selectBoxOverlay);

                element.hide().after(self.container);
            },

            addCssClasses = function (){
                self.container.addClass(skin.cssClass)
                    .addClass(imageListExpansion.cssClass)
                    .addClass(theme.cssClass)
                    .addClass(options.additionalClass);
            },

            pushImages = function (){
                var selectedImage = '';

                element.children().each(function (k){
                    var img = $('<img>'),
                        imageClass = 'sh-img-dropdown-image-' + k;

                    img.attr({
                        'src': $(this).data('image'),
                        'class': imageClass,
                        'alt': ''
                    });

                    self.list.append(img);

                    $(this).addClass(imageClass);

                    if($(this).is(':selected')) {
                        selectedImage = imageClass;
                    }
                });

                return selectedImage;
            };

        (function (){
            var selectedImageClass,
                imagesParents,
                scrollbar,
                eventType;

            addElementsToDom();
            addCssClasses();
            selectedImageClass = pushImages();

            effects.pushEffect(imageListExpansion.effect);
            effects.pushEffect(options.dropdownEffect);

            self.list.imagesLoaded(function (images){
                var listWrapperSize = 0,
                    listScreenSize = 0,
                    extraWidth;

                images.each(function (k){
                    var imgWrapper = $('<div></div>').addClass('sh-img-dropdown-image-wrapper'),
                        textBlock = $('<div></div>').addClass('sh-img-dropdown-textblock');

                    textBlock.text(element.children('.' + $(this).attr('class')).text());

                    imgWrapper.append($(this), textBlock);

                    if (options.imageLimit === 0 || options.imageLimit > k) {
                        imgWrapper.appendTo(self.listScreen);
                        listScreenSize += imgWrapper[imageListExpansion.expandSize]();

                    } else {
                        imgWrapper.appendTo(self.list);
                    }

                    listWrapperSize += imgWrapper[imageListExpansion.expandSize]();
                });

                self.list.css(imageListExpansion.expandSize, listWrapperSize);
                self.listScreen.css(imageListExpansion.expandSize, listScreenSize);
                self.listContainer.css(imageListExpansion.expandSize, listScreenSize);

                if ($.objectSize(imageListExpansion.containerOffset)) {
                    $.each(imageListExpansion.containerOffset, function (event, property){
                        var size,
                            propName,
                            newProperty = {};

                        for(propName in property) {
                            if (propName === 'margin-right' ||
                                propName === 'margin-left') {
                                size = 'width';

                            } else {
                                size = 'height';
                            }
                        }

                        newProperty[propName] = $.getPercentagePart(self.listContainer[size](), property[propName]);

                        $.addToObject(effects.currentEffect[event], newProperty);
                    });
                }

                self.listContainer.shScrollbar({
                    axis: imageListExpansion.scrollbarAxis,
                    skin: skin.scrollbarSkin,
                    contentElement: '.sh-img-dropdown-list'
                });

                scrollbar = self.listContainer.data('sh-scrollbar');

                eventHandlers(scrollbar);

                if(imageListExpansion.extraWidth) {
                    extraWidth = scrollbar.wrapper.width();
                    self.listContainer.css('width', '+=' + extraWidth);
                }

                self.listContainer.hide();

                setSelectedImage(images.filter('.' + selectedImageClass));

                $.shHelper.callCallback(options.callbacks.onImagesReady, self);

                imagesParents = images.parent();

                if ('ontouchstart' in document.documentElement) {
                    imagesParents.bind('touchstart', eventHandlers.touch.doTouchStartImageActions)
                        .bind('touchend', eventHandlers.touch.doTouchEndImageActions);

                    eventType = 'touchstart';

                } else {
                    imagesParents.bind('mouseover', eventHandlers.mouse.doMouseOverImageActions)
                        .bind('mouseout', eventHandlers.mouse.doMouseOutImageActions)
                        .bind('click', eventHandlers.mouse.doMouseClickImageActions);

                    eventType = 'click';
                }

                self.selectBoxOverlay.bind(eventType, eventHandlers.toggleDropDownListVisibility);

                $.each(theme.events, function (eventName, eventProps){
                    eventProps.element.bind(eventName, eventProps.handler);
                });
            });
        }());
    };
})(jQuery);

/*! jQuery SH Image Select v1.1.3, Copyright 2012 Alexy Vauch! */
(function($) {
    /**
     * Creates a list of images for all matched elements.
     *
     * @example $("#select").shImageSelect();
     * @input <select id="select">
     * "data-image" attribute with image link value required. e.g.: <option data-image="image.jpg">image</option>
     *
     * @method shImageSelect
     * @return $
     * @param o {Object} A set of configuration properties (key/value pairs).
     */
    $.fn.shImageSelect = function(o){
        var defaults = {
                mode: 'checkbox', // Selection mode. Allowed values: 'checkbox', 'radio'.
                maxSelected: 0, // Limits the maximum number of images that can be selected. "0" - Unlim.
                axis: 'y', // Vertical/horizontal scroller. Allowed values: "x", "y".
                imageLimit: {
                    x: 3, // Limits the maximum number of visible images on the x axis. "0" - Unlim.
                    y: 3 // Limits the maximum number of visible images on the y axis. "0" - Unlim.
                },
                showText: true, // Show select option text over image? // Allowed values: true, false.
                additionalClass: '', // Name of additional plugin html container css class.
                theme: 'box', // Theme name.
                skin: 'light', // Skin name. Allowed values: "light", "dark", "naked"
                callbacks: { // Callback functions
                    onSelect: '',
                    onUnselect: '',
                    onSelectedLimit: '',
                    onImagesReady: ''
                }
            },
            options;

        options = $.extend(true, defaults, o);

        return this.each(function (){
            $(this).data('sh-image-select', new shImageSelect($(this), options));
        });
    };

    /**
     * @constructor
     * @class shImageSelect
     * @param element {HTMLElement} The element to create the image list for.
     * @param options {Object} A set of configuration properties (key/value pairs).
     */
    var shImageSelect = function (element, options){
        var self = this;

        this.wrapper = $('<div></div>').addClass('sh-img-select-wrapper');
        this.container = $('<div></div>').addClass('sh-img-select-container');
        this.screenContainer = $('<div></div>').addClass('sh-img-select-screen-container');

        /**
         * Selects one image from images list.
         *
         * @method select
         * @return undefined
         */
        this.select = function (image){
            if (typeof image !== 'object') {
                image = self.container.find('.sh-img-select-image-' + image);
            }

            selectImage(image);
        };

        /**
         * Unselects one image from images list.
         *
         * @method unselect
         * @return undefined
         */
        this.unselect = function (image){
            if (typeof image !== 'object') {
                image = self.container.find('.sh-img-select-image-' + image);
            }

            unselectImage(image);
        };

        var eventHandlers = function (scrollbar){
                var toggleImageSelect = (function (){
                        var selectMethod;

                        if (options.mode === 'radio') {
                            selectMethod = function (imageContainer){
                                var image = imageContainer.children('img'),
                                    imageClass = image.attr('class'),
                                    alreadySelected = false;

                                element.children('[selected]').each(function (){
                                    var image = self.screenContainer.find('.' + $(this).attr('class'));

                                    if (imageClass != $(this).attr('class')) {
                                        unselectImage(image);

                                    } else {
                                        alreadySelected = true;
                                    }

                                    return alreadySelected;
                                });

                                if (!alreadySelected) {
                                    selectImage(image);
                                }
                            }

                        } else {
                            selectMethod = function (imageContainer){
                                var image = imageContainer.children('img');

                                if(isSelected(imageContainer)) {
                                    unselectImage(image);

                                } else {
                                    selectImage(image);
                                }
                            }
                        }

                        return selectMethod;
                    })(),

                    showImageText = function (imageTextBlock){
                        imageTextBlock.stop().animate({
                            bottom: '5px'
                        }, 'fast');
                    },

                    hideImageText = function (imageTextBlock){
                        imageTextBlock.stop().animate({
                            bottom: '-100%'
                        }, 'fast');
                    },

                    lightOffImage = function (image){
                        image.stop().fadeTo(300, 0.6);
                    },

                    lightOnImage = function (image){
                        image.stop().fadeTo(300, 1);
                    },

                    doImageNonSelectActionsTouch,
                    undoImageNonSelectActionsTouch,
                    doImageNonSelectActionsMouse,
                    undoImageNonSelectActionsMouse;

                if(options.showText) {
                    doImageNonSelectActionsMouse = function (imageContainer){
                        lightOffImage(imageContainer.children('img'));
                        showImageText(imageContainer.children('.sh-img-select-textblock'));
                    };

                    undoImageNonSelectActionsMouse = function (imageContainer){
                        lightOnImage(imageContainer.children('img'));
                        hideImageText(imageContainer.children('.sh-img-select-textblock'));
                    };

                    doImageNonSelectActionsTouch = doImageNonSelectActionsMouse;
                    undoImageNonSelectActionsTouch = undoImageNonSelectActionsMouse;

                } else {
                    doImageNonSelectActionsMouse = function (imageContainer){
                        lightOffImage(imageContainer.children('img'));
                    };

                    undoImageNonSelectActionsMouse = function (imageContainer){
                        lightOnImage(imageContainer.children('img'));
                    };

                    doImageNonSelectActionsTouch = function (imageContainer){};
                    undoImageNonSelectActionsTouch = doImageNonSelectActionsTouch;
                }

                eventHandlers = {
                    mouse: {
                        doMouseClickImageActions: function (e){
                            e.preventDefault();

                            toggleImageSelect($(this));
                        },

                        doMouseOverImageActions: function (){
                            doImageNonSelectActionsMouse($(this));

                        },

                        doMouseOutImageActions: function (){
                            undoImageNonSelectActionsMouse($(this));

                        }
                    },

                    touch: (function (){
                        var fingerOn = false,
                            imageTextIsVisible = false;

                        return {
                            doTouchStartImageActions: function (e){
                                var imageContainer = $(this);

                                e.preventDefault();

                                fingerOn = true;

                                setTimeout(function (){
                                    var draggingInEventsCycleFlag = scrollbar.draggingInEventsCycleFlag();

                                    if (!draggingInEventsCycleFlag) {
                                        if (fingerOn) {
                                            doImageNonSelectActionsTouch(imageContainer);
                                            imageTextIsVisible = true;

                                        } else {
                                            toggleImageSelect(imageContainer);
                                        }
                                    }
                                }, 200)
                            },

                            doTouchEndImageActions: function (){
                                fingerOn = false;

                                if (imageTextIsVisible) {
                                    undoImageNonSelectActionsTouch($(this));
                                    imageTextIsVisible = false;
                                }
                            }
                        }
                    })()
                }
            },

            selectImage = function (image){
                if(selectedCounter.increment()) {
                    element.children('.' + image.attr('class')).attr('selected', 'selected')
                        .end()
                        .triggerHandler('change');

                    toggleSelectedLayer(image, 'select', options.callbacks.onSelect);
                }
            },

            unselectImage = function (image){
                selectedCounter.decrement();

                element.children('.' + image.attr('class')).removeAttr('selected')
                    .end()
                    .triggerHandler('change');

                toggleSelectedLayer(image, 'unselect', options.callbacks.onUnselect);
            },

            toggleSelectedLayer = function (image, action, callback){
                var selectedLayer = image.parent().children('.sh-img-select-selected-layer');

                if(action == 'select') {
                    selectedLayer.fadeTo('fast', theme.selectedLayerOpacity);

                } else {
                    selectedLayer.fadeOut('fast');
                }

                if(callback) {
                    $.shHelper.callCallback(callback, image, self);
                }
            },

            isSelected = function (imageWrapper){
                return imageWrapper.children('.sh-img-select-selected-layer').is(':visible');
            },

            selectedCounter = {
                count: 0,
                increment: function (){
                    if(this.isLimit()) {
                        $.shHelper.callCallback(options.callbacks.onSelectedLimit, self);
                        return false;
                    }

                    this.count++;
                    return true;
                },

                decrement: function (){
                    this.count--;
                },

                isLimit: function (){
                    return (options.maxSelected !== 0 && this.count >= options.maxSelected)
                }
            },

            theme = (function (){
                var themeName = options.theme,
                    cssClass = 'sh-img-select-theme-',
                    selectedLayerOpacity = 0.6;

                switch (themeName) {
                    case 'box':
                        cssClass += themeName;
                        break;
                    case 'minimal':
                        selectedLayerOpacity = 1;
                        cssClass += themeName;
                        break;
                    default:
                        cssClass += themeName;
                }

                return {
                    cssClass: cssClass,
                    selectedLayerOpacity: selectedLayerOpacity
                };
            })(),

            skin = (function (){
                var skinName = options.skin,
                    cssClass = 'sh-img-select-',
                    scrollbarSkin = 'light';

                switch (skinName) {
                    case 'dark':
                        cssClass += skinName;
                        scrollbarSkin = skinName;
                        break;
                    case 'light':
                        cssClass += skinName;
                        scrollbarSkin = skinName;
                        break;
                    default:
                        cssClass += skinName;
                        scrollbarSkin = skinName;
                }

                return {
                    cssClass: cssClass,
                    scrollbarSkin: scrollbarSkin
                };

            })();

        (function (){
            var selectedImages = [];

            if(!element.attr('multiple')) {
                element.attr('multiple', 'multiple');
            }

            self.container.append(self.screenContainer);
            self.wrapper.append(self.container);
            element.hide().after(self.wrapper);

            self.wrapper.addClass(theme.cssClass)
                .addClass(skin.cssClass)
                .addClass(options.additionalClass);

            element.children().each(function (k){
                var img = $('<img>'),
                    imgClass = 'sh-img-select-image-' + k;

                self.container.append(img);

                img.attr({
                    'src': $(this).data('image'),
                    'class': imgClass,
                    'style': 'max-width:75px; height:75px;'
                });

                $(this).addClass(imgClass);

                if($(this).is(':selected')) {
                    selectedCounter.increment();
                    selectedImages.push(img);
                }
            });

            self.container.imagesLoaded(function (images){
                var imagesAxisX = 0,
                    screen = $('<div></div>').addClass('sh-img-select-screen'),
                    firstScreen = screen,
                    currentScreen,
                    screenId = 0,
                    screenLimit = options.imageLimit.x * options.imageLimit.y,
                    imageParents;

                images.each(function (){
                    var imgWrapper = $('<div></div>').addClass('sh-img-select-image-wrapper'),
                        textBlock = $('<div></div>').addClass('sh-img-select-textblock'),
                        selectedLayer = $('<div></div>').addClass('sh-img-select-selected-layer');

                    if(imagesAxisX == options.imageLimit.x) {
                        imgWrapper.css('clear', 'left');
                        imagesAxisX = 0;
                    }

                    if(!currentScreen || currentScreen.children().length == screenLimit) {
                        currentScreen = screen.clone().appendTo(self.screenContainer);
                        screenId += 1;
                    }

                    textBlock.text(element.children('.' + $(this).attr('class')).text());

                    imgWrapper.append($(this), textBlock, selectedLayer).appendTo(currentScreen);

                    if(screenId == 1) {
                        firstScreen = currentScreen;
                    }

                    imagesAxisX += 1;
                });

                imageParents = images.parent();

                $.each(selectedImages, function (k, image){
                    toggleSelectedLayer(image, 'select');
                });

                if(options.axis == 'y') {
                    self.screenContainer.children().css('clear', 'both');

                    self.container.css({
                        'min-width': firstScreen.width(),
                        'height': firstScreen.height()
                    });

                } else {
                    var newWidth = 0;

                    self.container.css({
                        'width': firstScreen.width(),
                        'min-height': firstScreen.height()
                    });

                    self.screenContainer.children().each(function (){
                        newWidth += $(this).width();
                    });

                    self.screenContainer.css('width', newWidth);
                }

                self.container.shScrollbar({
                    axis: options.axis,
                    skin: skin.scrollbarSkin,
                    contentElement: '.sh-img-select-screen-container'
                });

                eventHandlers(self.container.data('sh-scrollbar'));

                $.shHelper.callCallback(options.callbacks.onImagesReady, self);

                if ('ontouchstart' in document.documentElement) {
                    imageParents.bind('touchstart', eventHandlers.touch.doTouchStartImageActions)
                        .bind('touchend', eventHandlers.touch.doTouchEndImageActions);

                } else {
                    imageParents.bind('click', eventHandlers.mouse.doMouseClickImageActions)
                        .bind('mouseover', eventHandlers.mouse.doMouseOverImageActions)
                        .bind('mouseout', eventHandlers.mouse.doMouseOutImageActions);
                }
            });
        }())
    };
})(jQuery);

/*! jQuery SH Scrollbar v1.0, Copyright 2012 Alexy Vauch! */
(function($) {
    /**
     * Adds customised scrollbar. Mobile devices friendly.
     *
     * @example $("#container").shScrollbar({contentElement: '#content_block'});
     *
     * @method shScrollbar
     * @return $
     * @param o {Object} A set of configuration properties (key/value pairs).
     */
    $.fn.shScrollbar = function (o){
        var defaults = {
                axis: 'y', // Vertical/horizontal scroller. Allowed values: "x", "y"
                contentElement: '', // The element to create the scrollbar for.
                skin: 'light', // Allowed values: "light", "dark"
                scrollLength: 30 // How many pixels must the mouse wheel scrolls at a time.
            },
            options;

        options = $.extend(true, defaults, o);

        return this.each(function (){
            var contentBlock = $(this).find(options.contentElement);

            if (contentBlock.length) {
                $(this).data('sh-scrollbar', new shScrollbar($(this), contentBlock, options));

            } else {
                alert('Could\'nt find content block.');
            }
        });
    };

    /**
     * @constructor
     * @class shScrollbar 1.1
     * @param container {HTMLElement} The container element for scrollbar and content block.
     * @param contentBlock {HTMLElement} The element to create the scrollbar for.
     * @param options {Object} A set of configuration properties (key/value pairs).
     */
    var shScrollbar = function (container, contentBlock, options){
        this.wrapper = $('<div></div>').addClass('sh-scrollbar-wrapper');
        this.track = $('<div></div>').addClass('sh-scrollbar-track');
        this.slider = $('<div></div>').addClass('sh-scrollbar-slider');

        this.draggingInEventsCycleFlag = function (){
            return eventHandlers.draggingInEventsCycleFlag();
        };

        var self = this,

            eventHandlers = (function (){
                var StartPointerOffsetCoords = {
                        x: 0,
                        y: 0
                    },
                    draggingInEventsCycleFlag = false;

                return {
                    touch: (function (){
                        return {
                            makeDraggable: function (e){
                                e.preventDefault();

                                draggingInEventsCycleFlag = false;

                                StartPointerOffsetCoords.y = e.originalEvent.touches[0].pageY;
                                StartPointerOffsetCoords.x = e.originalEvent.touches[0].pageX;

                                offset.stopDrifting();
                                offset.saveSliderOffset();
                            },

                            dragging: function (e){
                                var fingerOffsetCoords = {
                                    'x': e.originalEvent.touches[0].pageX,
                                    'y': e.originalEvent.touches[0].pageY
                                };

                                e.stopPropagation();

                                draggingInEventsCycleFlag = true;

                                nonstopMoving.adjust(fingerOffsetCoords[options.axis]);
                                offset.setNewOffsetTouch(fingerOffsetCoords[options.axis] - StartPointerOffsetCoords[options.axis]);
                                self.slider.addClass('sh-scrollbar-draggable');
                            },

                            stopDragging: function (){
                                var nonstopMovingParams = nonstopMoving.stop();

                                if (nonstopMovingParams.offset) {
                                    offset.saveSliderOffset();
                                    offset.setNewOffsetTouchDrift(nonstopMovingParams.offset, nonstopMovingParams.animationSpeed);
                                }

                                self.slider.removeClass('sh-scrollbar-draggable');
                            }
                        }
                    })(),

                    mouse: (function (){
                        var selectEventName = $.support.selectstart ? 'selectstart' : 'mousedown';

                        return {
                            makeDraggable: function (e){
                                draggingInEventsCycleFlag = false;

                                StartPointerOffsetCoords.y = e.pageY;
                                StartPointerOffsetCoords.x = e.pageX;

                                offset.saveSliderOffset();

                                $(document).bind('mousemove', eventHandlers.mouse.dragging)
                                    .bind('mouseup', eventHandlers.mouse.stopDragging)
                                    .bind(selectEventName + '.disableSelection', function (e){e.preventDefault();});

                                $(this).addClass('sh-scrollbar-draggable');
                            },

                            dragging: function(e){
                                var cursorOffsetCoords = {
                                    'x': e.pageX,
                                    'y': e.pageY
                                };

                                e.stopPropagation();

                                draggingInEventsCycleFlag = true;

                                offset.setNewOffsetMouseFire(cursorOffsetCoords[options.axis] - StartPointerOffsetCoords[options.axis]);
                            },

                            stopDragging: function (){
                                $(document).unbind('mousemove', eventHandlers.mouse.dragging)
                                    .unbind('mouseup', eventHandlers.mouse.stopDragging)
                                    .unbind(selectEventName + '.disableSelection');

                                self.slider.removeClass('sh-scrollbar-draggable');
                            },

                            wheellScrolling: function(e, delta) {
                                e.stopPropagation();
                                e.preventDefault();

                                offset.setNewOffsetMouseScroll(delta * options.scrollLength);
                            }
                        }
                    })(),

                    draggingInEventsCycleFlag: function (){
                        return draggingInEventsCycleFlag;
                    }
                }
            })(),

            nonstopMoving = (function (){
                var movingStartPosition = 0,
                    movingLastPosition = 0,
                    movingTimeStart = 0,
                    movingTimeLast = 0,
                    currentDirectionSign = '-',

                    isNewDirection = function (currentPosition){
                        var oldDirectionSign = currentDirectionSign;

                        if (currentPosition !== movingLastPosition) {
                            if (currentPosition - movingLastPosition < 0 && currentDirectionSign === '+') {
                                currentDirectionSign = '-';

                            } else if(currentPosition - movingLastPosition > 0 && currentDirectionSign === '-') {
                                currentDirectionSign = '+';

                            }
                        }

                        return (oldDirectionSign !== currentDirectionSign);
                    },

                    stopMoving = function (){
                        var time = 0,
                            distance = 0,
                            currentTime = $.microtime();

                        if (movingTimeStart && movingTimeLast &&
                            movingStartPosition && movingLastPosition &&
                            currentTime - movingTimeLast < 0.07) {
                            time = currentTime - movingTimeStart;

                            if (movingStartPosition > movingLastPosition) {
                                distance = movingStartPosition - movingLastPosition;

                            } else {
                                distance = movingLastPosition - movingStartPosition;
                            }
                        }

                        return {
                            time: time,
                            distance: distance
                        };
                    };

                return {
                    adjust: function (currentPosition){
                        var currentTime = $.microtime();

                        if (isNewDirection(currentPosition)) {
                            movingTimeStart = currentTime;
                            movingStartPosition = currentPosition;
                        }

                        movingTimeLast = currentTime;
                        movingLastPosition = currentPosition;
                    },

                    stop: function (){
                        var nonstopMovingParams = stopMoving(),
                            offsetValue = false,
                            offsetSign = (currentDirectionSign === '-') ? '-' : '',
                            speed;

                        speed = nonstopMovingParams.distance / nonstopMovingParams.time;

                        if (speed > 200 && speed < 1000) {
                            offsetValue = offsetSign + nonstopMovingParams.distance;

                        } else if (speed >= 1000) {
                            offsetValue = offsetSign + 9999;
                        }

                        return {
                            animationSpeed: speed,
                            offset: offsetValue
                        }
                    }
                }
            }()),

            offset = function (elements, offsetFrom, sizeVector){
                var trackFinishOffset,
                    containerSize,
                    contentBlockSize,
                    sliderOffset = 0,
                    sliderOffsetSaved = 0,
                    trackStartOffset = 0,

                    setNewOffset = function (nextContentOffset, nextSliderOffset){
                        contentBlock.css(offsetFrom, nextContentOffset);
                        elements.slider.css(offsetFrom, nextSliderOffset);
                    },

                    calculateContentOffset = function (sliderOffset){
                        return - (contentBlockSize - containerSize) * sliderOffset / trackFinishOffset;
                    },

                    sanitizeSliderOffset = function (sliderOffset){
                        if(sliderOffset < trackStartOffset) {
                            sliderOffset = trackStartOffset;

                        } else if(sliderOffset  > trackFinishOffset) {
                            sliderOffset = trackFinishOffset;
                        }

                        return sliderOffset;
                    },

                    isEqualCurrentSliderOffset = function (value){
                        return (value === sliderOffset);
                    },

                    setNewOffsetMouse = function (nextSliderOffset){
                        nextSliderOffset = sanitizeSliderOffset(nextSliderOffset);

                        if (!isEqualCurrentSliderOffset(nextSliderOffset)) {
                            setNewOffset(calculateContentOffset(nextSliderOffset), nextSliderOffset);
                            sliderOffset = nextSliderOffset;
                        }
                    },

                    calculateTouchOffset = function (fingerPosition, callback){
                        var nextSliderOffset,
                            adjustedFingerPosition;

                        nextSliderOffset = - trackFinishOffset * fingerPosition / (contentBlockSize - containerSize) + sliderOffsetSaved;
                        nextSliderOffset = sanitizeSliderOffset(nextSliderOffset);
                        adjustedFingerPosition = calculateContentOffset(nextSliderOffset);

                        if (!isEqualCurrentSliderOffset(nextSliderOffset)) {
                            if (typeof callback === 'function') {
                                callback.call(null, adjustedFingerPosition, nextSliderOffset);
                            }
                            sliderOffset = nextSliderOffset;
                        }
                    };

                offset = {
                    saveSliderOffset: function (){
                        sliderOffsetSaved = sliderOffset;
                    },

                    setNewOffsetTouch: function (fingerPosition){
                        calculateTouchOffset(fingerPosition, function (adjustedFingerPosition, nextSliderOffset){
                            setNewOffset(adjustedFingerPosition, nextSliderOffset);
                        });
                    },

                    setNewOffsetTouchDrift: function (fingerPosition, animationSpeed){
                        calculateTouchOffset(fingerPosition, function (nextContentOffset, nextSliderOffset){
                            var contentBlockAnimationProp = {},
                                sliderAnimationProp = {};

                            contentBlockAnimationProp[offsetFrom] = nextContentOffset;
                            sliderAnimationProp[offsetFrom] = nextSliderOffset;

                            elements.slider.animate(sliderAnimationProp, animationSpeed);
                            contentBlock.animate(contentBlockAnimationProp, animationSpeed);
                        });
                    },

                    stopDrifting: function (){
                        elements.slider.stop();
                        contentBlock.stop();

                        var currentSliderOffset = elements.slider.css(offsetFrom);
                        sliderOffset = (currentSliderOffset === 'auto') ? 0 : parseInt(currentSliderOffset);
                    },

                    setNewOffsetMouseScroll: function (nextSliderOffset){
                        nextSliderOffset = sliderOffset - nextSliderOffset;
                        setNewOffsetMouse(nextSliderOffset);
                    },

                    setNewOffsetMouseFire: function (nextSliderOffset){
                        nextSliderOffset += sliderOffsetSaved;
                        setNewOffsetMouse(nextSliderOffset);
                    }
                };

                trackFinishOffset = elements.track[sizeVector]() - elements.slider[sizeVector]();
                containerSize = elements.container[sizeVector]();
                contentBlockSize = elements.contentBlock[sizeVector]();
            },

            skinClass = (function (){
                var skinClass;

                switch (options.skin) {
                    case 'light':
                        skinClass = 'sh-scrollbar-light';
                        break;
                    case 'dark':
                        skinClass = 'sh-scrollbar-dark';
                        break;
                    default:
                        skinClass = 'sh-scrollbar-dark';
                }

                return skinClass;
            })();

        (function (){
            var axisClass,
                sizeVector,
                offsetFrom;

            contentBlock.css('position', 'relative');

            self.track.append(self.slider).appendTo(self.wrapper);
            self.wrapper.appendTo(container);

            if(options.axis === 'y') {
                axisClass = 'sh-scrollbar-track-y';
                sizeVector = 'height';
                offsetFrom = 'top';

            } else {
                axisClass = 'sh-scrollbar-track-x';
                sizeVector = 'width';
                offsetFrom = 'left';
            }

            self.wrapper.addClass(skinClass)
                .addClass(axisClass);

            if(contentBlock[sizeVector]() <= container[sizeVector]()) {
                self.wrapper.remove();
                return;
            }

            offset({
                container: container,
                contentBlock: contentBlock,
                track: self.track,
                slider: self.slider
            }, offsetFrom, sizeVector);

            if ('ontouchstart' in document.documentElement) {
                contentBlock.bind('touchstart', eventHandlers.touch.makeDraggable)
                    .bind('touchmove', eventHandlers.touch.dragging)
                    .bind('touchend', eventHandlers.touch.stopDragging);

            } else {
                contentBlock.mousewheel(eventHandlers.mouse.wheellScrolling);
                self.slider.bind('mousedown', eventHandlers.mouse.makeDraggable);
            }
        }());
    };
})(jQuery);

/*! SH helper functions */
(function($) {
    $.extend({
        shHelper: {
            callCallback: function (callback, newThis, args){
                return (typeof callback === 'function') ? callback.call(newThis, args) : false;
            }
        },

        addToObject: function (target, object){
            $.each(object, function (k, v){
                if (false === k in target) {
                    target[k] = v;
                }
            });

            return target;
        },

        microtime: function (){
            return new Date().getTime() / 1000;
        },

        objectSize: function(obj) {
            var size = 0;

            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    size++;
                }
            }

            return size;
        },

        getPercentagePart: function (number, percentString) {
            var percent = parseInt(percentString.replace('%', ''));

            return number / 100 * percent;
        }
    });
})(jQuery);

/**
 * "jQuery Mouse Wheel" - specifies the use of the mouse wheel..
 * @see https://github.com/brandonaaron/jquery-mousewheel for more info.
 */
(function(d){function e(a){var b=a||window.event,c=[].slice.call(arguments,1),f=0,e=0,g=0;a=d.event.fix(b);a.type="mousewheel";b.wheelDelta&&(f=b.wheelDelta/120);b.detail&&(f=-b.detail/3);g=f;void 0!==b.axis&&b.axis===b.HORIZONTAL_AXIS&&(g=0,e=-1*f);void 0!==b.wheelDeltaY&&(g=b.wheelDeltaY/120);void 0!==b.wheelDeltaX&&(e=-1*b.wheelDeltaX/120);c.unshift(a,f,e,g);return(d.event.dispatch||d.event.handle).apply(this,c)}var c=["DOMMouseScroll","mousewheel"];if(d.event.fixHooks)for(var h=c.length;h;)d.event.fixHooks[c[--h]]=
    d.event.mouseHooks;d.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=c.length;a;)this.addEventListener(c[--a],e,!1);else this.onmousewheel=e},teardown:function(){if(this.removeEventListener)for(var a=c.length;a;)this.removeEventListener(c[--a],e,!1);else this.onmousewheel=null}};d.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);

/**
 * "imagesLoaded" - cached images loader.
 * @see http://desandro.github.com/imagesloaded/ for more info.
 */
(function(c,n){var l="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";c.fn.imagesLoaded=function(f){function m(){var b=c(i),a=c(h);d&&(h.length?d.reject(e,b,a):d.resolve(e));c.isFunction(f)&&f.call(g,e,b,a)}function j(b,a){b.src===l||-1!==c.inArray(b,k)||(k.push(b),a?h.push(b):i.push(b),c.data(b,"imagesLoaded",{isBroken:a,src:b.src}),o&&d.notifyWith(c(b),[a,e,c(i),c(h)]),e.length===k.length&&(setTimeout(m),e.unbind(".imagesLoaded")))}var g=this,d=c.isFunction(c.Deferred)?c.Deferred():
    0,o=c.isFunction(d.notify),e=g.find("img").add(g.filter("img")),k=[],i=[],h=[];c.isPlainObject(f)&&c.each(f,function(b,a){if("callback"===b)f=a;else if(d)d[b](a)});e.length?e.bind("load.imagesLoaded error.imagesLoaded",function(b){j(b.target,"error"===b.type)}).each(function(b,a){var d=a.src,e=c.data(a,"imagesLoaded");if(e&&e.src===d)j(a,e.isBroken);else if(a.complete&&a.naturalWidth!==n)j(a,0===a.naturalWidth||0===a.naturalHeight);else if(a.readyState||a.complete)a.src=l,a.src=d}):m();return d?d.promise(g):
    g}})(jQuery);