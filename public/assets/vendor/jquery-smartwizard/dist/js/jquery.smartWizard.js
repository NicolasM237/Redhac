/*!
 * jQuery SmartWizard v5.1.1 (Modernized)
 * http://www.techlaboratory.net/jquery-smartwizard
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = (root, jQuery) => {
            if (jQuery === undefined) {
                jQuery = (typeof window !== 'undefined') ? require('jquery') : require('jquery')(root);
            }
            factory(jQuery);
            return jQuery;
        };
    } else {
        factory(jQuery);
    }
}(function ($) {
    "use strict";

    const defaults = {
        selected: 0,
        theme: 'default',
        justified: true,
        darkMode: false,
        autoAdjustHeight: true,
        cycleSteps: false,
        backButtonSupport: true,
        enableURLhash: true,
        transition: {
            animation: 'none',
            speed: '400',
            easing: ''
        },
        toolbarSettings: {
            toolbarPosition: 'bottom',
            toolbarButtonPosition: 'right',
            showNextButton: true,
            showPreviousButton: true,
            toolbarExtraButtons: []
        },
        anchorSettings: {
            anchorClickable: true,
            enableAllAnchors: false,
            markDoneStep: true,
            markAllPreviousStepsAsDone: true,
            removeDoneStepOnNavigateBack: false,
            enableAnchorOnDoneStep: true
        },
        keyboardSettings: {
            keyNavigation: true,
            keyLeft: [37],
            keyRight: [39]
        },
        lang: {
            next: 'Suivant',
            previous: 'Précédent'
        },
        disabledSteps: [],
        errorSteps: [],
        hiddenSteps: []
    };

    class SmartWizard {
        constructor(element, options) {
            this.options = $.extend(true, {}, defaults, options);
            this.main = $(element);
            this.nav = this._getFirstDescendant('.nav');
            this.steps = this.nav.find('.nav-link');
            this.container = this._getFirstDescendant('.tab-content');
            this.pages = this.container.children('.tab-pane');
            this.current_index = null;

            this._initOptions();
            this._initLoad();
        }

        _initLoad() {
            this.pages.hide();
            this.steps.removeClass('done active');
            const idx = this._getStepIndex();
            this._setPreviousStepsDone(idx);
            this._showStep(idx);
        }

        _initOptions() {
            this._setElements();
            this._setToolbar();
            this._setEvents();
        }

        _getFirstDescendant(selector) {
            let elm = this.main.children(selector);
            if (elm.length > 0) return elm;

            this.main.children().each((i, n) => {
                let tmp = $(n).children(selector);
                if (tmp.length > 0) {
                    elm = tmp;
                    return false;
                }
            });

            return elm.length > 0 ? elm : (console.error("Element not found: " + selector), false);
        }

        _setElements() {
            this.main.addClass('sw');
            this._setTheme(this.options.theme);
            this._setJustify(this.options.justified);
            this._setDarkMode(this.options.darkMode);

            if (!this.options.anchorSettings.enableAllAnchors || !this.options.anchorSettings.anchorClickable) {
                this.steps.addClass('inactive');
            }

            this._setCSSClass(this.options.disabledSteps, "disabled");
            this._setCSSClass(this.options.errorSteps, "danger");
            this._setCSSClass(this.options.hiddenSteps, "hidden");
        }

        _setEvents() {
            if (this.main.data('click-init')) return true;
            this.main.data('click-init', true);

            this.steps.on("click", (e) => {
                e.preventDefault();
                if (!this.options.anchorSettings.anchorClickable) return true;
                
                const idx = this.steps.index(e.currentTarget);
                if (idx === this.current_index) return true;
                if (!this.options.anchorSettings.enableAnchorOnDoneStep && this._isDone(idx)) return true;

                if (this.options.anchorSettings.enableAllAnchors || this._isDone(idx)) {
                    this._showStep(idx);
                }
            });

            this.main.find('.sw-btn-next').on("click", (e) => {
                e.preventDefault();
                this._showNext();
            });

            this.main.find('.sw-btn-prev').on("click", (e) => {
                e.preventDefault();
                this._showPrevious();
            });

            if (this.options.keyboardSettings.keyNavigation) {
                $(document).keyup((e) => this._keyNav(e));
            }

            if (this.options.backButtonSupport) {
                $(window).on('hashchange', (e) => {
                    const idx = this._getURLHashIndex();
                    if (idx !== false) {
                        e.preventDefault();
                        this._showStep(idx);
                    }
                });
            }
        }

        _setToolbar() {
            const pos = this.options.toolbarSettings.toolbarPosition;
            if (pos === 'none') return;

            if (pos === 'top' || pos === 'both') this.container.before(this._createToolbar('top'));
            if (pos === 'bottom' || pos === 'both' || pos === 'default') this.container.after(this._createToolbar('bottom'));
        }

        _createToolbar(position) {
            if (this.main.find('.toolbar-' + position).length > 0) return null;

            const toolbar = $('<div></div>').addClass('toolbar toolbar-' + position).attr('role', 'toolbar');
            const btnNext = this.options.toolbarSettings.showNextButton ? $('<button></button>').text(this.options.lang.next).addClass('btn btn-primary sw-btn-next').attr('type', 'button') : null;
            const btnPrev = this.options.toolbarSettings.showPreviousButton ? $('<button></button>').text(this.options.lang.previous).addClass('btn btn-primary sw-btn-prev').attr('type', 'button') : null;
            
            toolbar.append(btnPrev, btnNext);

            if (this.options.toolbarSettings.toolbarExtraButtons?.length > 0) {
                this.options.toolbarSettings.toolbarExtraButtons.forEach(btn => toolbar.append(btn.clone(true)));
            }

            toolbar.css('text-align', this.options.toolbarSettings.toolbarButtonPosition);
            return toolbar;
        }

        _showNext() {
            const si = this._getNextShowable(this.current_index);
            if (si !== false) this._showStep(si);
        }

        _showPrevious() {
            const si = this._getPreviousShowable(this.current_index);
            if (si !== false) this._showStep(si);
        }

        _showStep(idx) {
            if (idx == this.current_index || !this.steps.eq(idx) || !this._isShowable(idx)) return false;
            this._loadStep(idx);
        }

        _getNextShowable(idx) {
            let si = false;
            for (let i = idx + 1; i < this.steps.length; i++) {
                if (this._isShowable(i)) { si = i; break; }
            }
            if (si === false && this.options.cycleSteps) si = 0;
            return si;
        }

        _getPreviousShowable(idx) {
            let si = false;
            for (let i = idx - 1; i >= 0; i--) {
                if (this._isShowable(i)) { si = i; break; }
            }
            if (si === false && this.options.cycleSteps) si = this.steps.length - 1;
            return si;
        }

        _isShowable(idx) {
            const elm = this.steps.eq(idx);
            return !(elm.hasClass('disabled') || elm.hasClass('hidden'));
        }

        _isDone(idx) {
            return this.steps.eq(idx).hasClass('done');
        }

        _setPreviousStepsDone(idx) {
            if (idx > 0 && this.options.anchorSettings.markDoneStep && this.options.anchorSettings.markAllPreviousStepsAsDone) {
                for (let i = idx; i >= 0; i--) this._setCSSClass(i, "done");
            }
        }

        _setCSSClass(idx, cls) {
            if (idx === null) return;
            const idxs = Array.isArray(idx) ? idx : [idx];
            idxs.forEach(i => this.steps.eq(i).addClass(cls));
        }

        _resetCSSClass(idx, cls) {
            const idxs = Array.isArray(idx) ? idx : [idx];
            idxs.forEach(i => this.steps.eq(i).removeClass(cls));
        }

        _getStepDirection(idx) {
            if (this.current_index == null) return '';
            return this.current_index < idx ? "forward" : "backward";
        }

        _getStepPosition(idx) {
            if (idx === 0) return 'first';
            if (idx === this.steps.length - 1) return 'last';
            return 'middle';
        }

        _getStepAnchor(idx) {
            return idx == null ? null : this.steps.eq(idx);
        }

        _getStepPage(idx) {
            if (idx == null) return null;
            const anchor = this._getStepAnchor(idx);
            return anchor.length > 0 ? this.main.find(anchor.attr("href")) : null;
        }

        _loadStep(idx) {
            const curStep = this._getStepAnchor(this.current_index);
            const stepDirection = this._getStepDirection(idx);

            if (this.current_index !== null) {
                if (this._triggerEvent("leaveStep", [curStep, this.current_index, idx, stepDirection]) === false) return false;
            }

            const selStep = this._getStepAnchor(idx);
            const content = this._triggerEvent("stepContent", [selStep, idx, stepDirection]);

            if (content && typeof content === "object" && typeof content.then === "function") {
                content.then(res => {
                    this._setStepContent(idx, res);
                    this._transitStep(idx);
                }).catch(err => {
                    console.error(err);
                    this._transitStep(idx);
                });
            } else {
                if (typeof content === "string") this._setStepContent(idx, content);
                this._transitStep(idx);
            }
        }

        _transitStep(idx) {
            const selStep = this._getStepAnchor(idx);
            this._setURLHash(selStep.attr("href"));
            this._setAnchor(idx);
            
            const direction = this._getStepDirection(idx);
            const position = this._getStepPosition(idx);

            this._doStepAnimation(idx, () => {
                this._fixHeight(idx);
                this._triggerEvent("showStep", [selStep, this.current_index, direction, position]);
            });

            this.current_index = idx;
            this._setButtons(idx);
        }

        _doStepAnimation(idx, callback) {
            const curPage = this._getStepPage(this.current_index);
            const selPage = this._getStepPage(idx);
            const animation = this.options.transition.animation.toLowerCase();

            this._stopAnimations();

            if (animation === 'fade') {
                if (curPage) curPage.fadeOut('fast', () => selPage.fadeIn('fast', callback));
                else selPage.fadeIn(this.options.transition.speed, callback);
            } else {
                if (curPage) curPage.hide();
                selPage.show();
                callback();
            }
        }

        _stopAnimations() {
            this.pages.finish();
            this.container.finish();
        }

        _setAnchor(idx) {
            this._resetCSSClass(this.current_index, "active");
            if (this.options.anchorSettings.markDoneStep && this.current_index !== null) {
                this._setCSSClass(this.current_index, "done");
                if (this.options.anchorSettings.removeDoneStepOnNavigateBack && this._getStepDirection(idx) === 'backward') {
                    this._resetCSSClass(this.current_index, "done");
                }
            }
            this._resetCSSClass(idx, "done");
            this._setCSSClass(idx, "active");
        }

        _setButtons(idx) {
            if (this.options.cycleSteps) return;
            const btnPrev = this.main.find('.sw-btn-prev').removeClass("disabled");
            const btnNext = this.main.find('.sw-btn-next').removeClass("disabled");

            const pos = this._getStepPosition(idx);
            if (pos === 'first') btnPrev.addClass("disabled");
            else if (pos === 'last') btnNext.addClass("disabled");
        }

        _setURLHash(hash) {
            if (this.options.enableURLhash && window.location.hash !== hash) {
                history.pushState(null, null, hash);
            }
        }

        _getURLHashIndex() {
            if (!this.options.enableURLhash) return false;
            const hash = window.location.hash;
            if (hash.length > 0) {
                const elm = this.nav.find(`a[href*='${hash}']`);
                if (elm.length > 0) return this.steps.index(elm);
            }
            return false;
        }

        _getStepIndex() {
            const idx = this._getURLHashIndex();
            return idx === false ? this.options.selected : idx;
        }

        _setTheme(theme) {
            this.main.removeClass((i, className) => (className.match(/(^|\s)sw-theme-\S+/g) || []).join(' '))
                     .addClass('sw-theme-' + theme);
        }

        _setJustify(justified) {
            this.main.toggleClass('sw-justified', justified);
        }

        _setDarkMode(darkMode) {
            this.main.toggleClass('sw-dark', darkMode);
        }

        _keyNav(e) {
            if (this.options.keyboardSettings.keyLeft.includes(e.which)) {
                this._showPrevious();
                e.preventDefault();
            } else if (this.options.keyboardSettings.keyRight.includes(e.which)) {
                this._showNext();
                e.preventDefault();
            }
        }

        _fixHeight(idx) {
            if (this.options.autoAdjustHeight) {
                const h = this._getStepPage(idx).outerHeight();
                this.container.finish().animate({ height: h }, this.options.transition.speed);
            }
        }

        _triggerEvent(name, params) {
            const e = $.Event(name);
            this.main.trigger(e, params);
            return e.isDefaultPrevented() ? false : e.result;
        }

        // Public Methods
        goToStep(idx) { this._showStep(idx); }
        next() { this._showNext(); }
        prev() { this._showPrevious(); }
        reset() {
            this._setURLHash('#');
            this._initOptions();
            this._initLoad();
        }
    }

    $.fn.smartWizard = function (options, ...args) {
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, "smartWizard")) {
                    $.data(this, "smartWizard", new SmartWizard(this, options));
                }
            });
        }
        
        const instance = $.data(this[0], 'smartWizard');
        if (options === 'destroy') $.data(this, 'smartWizard', null);
        
        if (instance instanceof SmartWizard && typeof instance[options] === 'function') {
            return instance[options].apply(instance, args);
        }
        return this;
    };
}));