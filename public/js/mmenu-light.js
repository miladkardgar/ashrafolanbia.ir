!function (t) {
    var e = {};

    function n(o) {
        if (e[o]) return e[o].exports;
        var s = e[o] = {i: o, l: !1, exports: {}};
        return t[o].call(s.exports, s, s.exports, n), s.l = !0, s.exports
    }

    n.m = t, n.c = e, n.d = function (t, e, o) {
        n.o(t, e) || Object.defineProperty(t, e, {enumerable: !0, get: o})
    }, n.r = function (t) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(t, "__esModule", {value: !0})
    }, n.t = function (t, e) {
        if (1 & e && (t = n(t)), 8 & e) return t;
        if (4 & e && "object" == typeof t && t && t.__esModule) return t;
        var o = Object.create(null);
        if (n.r(o), Object.defineProperty(o, "default", {
            enumerable: !0,
            value: t
        }), 2 & e && "string" != typeof t) for (var s in t) n.d(o, s, function (e) {
            return t[e]
        }.bind(null, s));
        return o
    }, n.n = function (t) {
        var e = t && t.__esModule ? function () {
            return t.default
        } : function () {
            return t
        };
        return n.d(e, "a", e), e
    }, n.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }, n.p = "", n(n.s = 0)
}([function (t, e, n) {
    "use strict";
    n.r(e);
    var o = function () {
            function t(t) {
                var e = this;
                this.listener = function (t) {
                    (t.matches ? e.matchFns : e.unmatchFns).forEach(function (t) {
                        t()
                    })
                }, this.toggler = window.matchMedia(t), this.toggler.addListener(this.listener), this.matchFns = [], this.unmatchFns = []
            }

            return t.prototype.destroy = function () {
                this.toggler.removeListener(this.listener), this.unmatchFns.forEach(function (t) {
                    t()
                }), this.matchFns = [], this.unmatchFns = []
            }, t.prototype.add = function (t, e) {
                this.matchFns.push(t), this.unmatchFns.push(e), (this.toggler.matches ? t : e)()
            }, t
        }(), s = {title: "Menu", theme: "light", slidingSubmenus: !0, selected: "Selected"},
        i = {blockPage: !0, move: !0, position: "left"},
        r = "ontouchstart" in window || !!navigator.msMaxTouchPoints || !1,
        a = navigator.userAgent.indexOf("MSIE") > -1 || navigator.appVersion.indexOf("Trident/") > -1,
        m = function (t) {
            return Array.prototype.slice.call(t)
        }, c = function (t, e) {
            return m((e || document).querySelectorAll(t))
        }, u = function () {
            function t(e, n) {
                var o = this;
                void 0 === n && (n = {}), this.options = {}, Object.keys(t.options).forEach(function (e) {
                    o.options[e] = void 0 !== n[e] ? n[e] : t.options[e]
                }), a && (this.options.slidingSubmenus = !1), this.menu = e, "dark" == this.options.theme && this.menu.classList.add("mm--dark"), this.options.slidingSubmenus || this.menu.classList.add("mm--vertical"), this._openPanel(), this._initAnchors()
            }

            return t.prototype.enable = function (t) {
                var e = this;
                return void 0 === t && (t = "all"), this.toggler = new o(t), this.toggler.add(function () {
                    return e.menu.classList.add("mm")
                }, function () {
                    return e.menu.classList.remove("mm")
                }), this.toggler
            }, t.prototype.disable = function () {
                this.toggler.destroy()
            }, t.prototype._openPanel = function () {
                var t = c("." + this.options.selected, this.menu), e = t[t.length - 1], n = null;
                e && (n = e.closest("ul")), n || (n = this.menu.querySelector("ul")), this.openPanel(n)
            }, t.prototype.openPanel = function (t) {
                var e = t.dataset.mmTitle, n = t.parentElement;
                n === this.menu ? this.menu.classList.add("mm--main") : (this.menu.classList.remove("mm--main"), e || m(n.children).forEach(function (t) {
                    t.matches("a, span") && (e = t.textContent)
                })), e || (e = this.options.title), this.menu.dataset.mmTitle = e, c(".mm--open", this.menu).forEach(function (t) {
                    t.classList.remove("mm--open", "mm--parent")
                }), t.classList.add("mm--open"), t.classList.remove("mm--parent");
                for (var o = t.parentElement.closest("ul"); o;) o.classList.add("mm--open", "mm--parent"), o = o.parentElement.closest("ul")
            }, t.prototype.togglePanel = function (t) {
                if (this.options.slidingSubmenus) this.openPanel(t); else {
                    var e = "add";
                    t.matches(".mm--open") && (e = "remove"), t.classList[e]("mm--open"), t.parentElement.classList[e]("mm--open")
                }
            }, t.prototype._initAnchors = function () {
                var t = this;
                this.menu.addEventListener("click", function (e) {
                    if (t.menu.matches(".mm")) {
                        var n = !1;
                        n = (n = (n = n || function (t) {
                            return !!t.target.matches("a") && (t.stopImmediatePropagation(), !0)
                        }(e)) || function (e) {
                            var n, o = e.target;
                            return !!(n = o.closest("span") ? o.parentElement : !!o.closest("li") && o) && (m(n.children).forEach(function (e) {
                                e.matches("ul") && t.togglePanel(e)
                            }), e.stopImmediatePropagation(), !0)
                        }(e)) || function (e) {
                            var n = e.target;
                            if (n.matches(".mm")) {
                                var o = c(".mm--open", n), s = o[o.length - 1];
                                if (s) {
                                    var i = s.parentElement.closest("ul");
                                    i && t.openPanel(i)
                                }
                                return e.stopImmediatePropagation(), !0
                            }
                            return !1
                        }(e)
                    }
                })
            }, t.version = "2.3.1", t.options = s, t.optionsOffcanvas = i, t
        }();
    u.prototype.open = function () {
        this.menu.matches(".mm") && (this.menu.classList.add("mm--open"), document.body.classList.add("mm-body--open"), this.blocker && this.blocker.classList.add("mm--open"), this.menu.dispatchEvent(new Event("mm:open")))
    }, u.prototype.close = function () {
        this.menu.classList.remove("mm--open"), document.body.classList.remove("mm-body--open"), this.blocker && this.blocker.classList.remove("mm--open"), this.menu.dispatchEvent(new Event("mm:close"))
    };
    /*!
     * mmenujs.com/mmenu-light
     *
     * Copyright (c) Fred Heusschen
     * www.frebsite.nl
     *
     * License: CC-BY-4.0
     * http://creativecommons.org/licenses/by/4.0/
     */
    u.prototype.offcanvas = function (t) {
        var e = this;
        void 0 === t && (t = {});
        var n, o = {};
        Object.keys(u.optionsOffcanvas).forEach(function (e) {
            o[e] = void 0 !== t[e] ? t[e] : u.optionsOffcanvas[e]
        }), this.menu.classList.add("mm--offcanvas"), this.toggler.add(function () {
        }, function () {
            e.close()
        }), "right" == o.position && this.menu.classList.add("mm--right"), o.move && this.toggler.add(function () {
            n = document.createComment("original menu location"), e.menu.after(n), document.body.append(e.menu)
        }, function () {
            n && n.replaceWith(e.menu)
        });
        o.blockPage && (this.blocker = document.createElement("div"), this.blocker.classList.add("mm-blocker"), "right" == o.position && this.blocker.classList.add("mm--right"), document.body.append(this.blocker), "modal" != o.blockPage && function () {
            var t = this;
            this.blocker.addEventListener(r ? "touchstart" : "mousedown", function (e) {
                return !!t.menu.matches(".mm") && !!t.menu.matches(".mm--open") && (t.close(), e.preventDefault(), e.stopImmediatePropagation(), !0)
            })
        }.call(this), this.toggler.add(function () {
            e.blocker.classList.remove("mm-hidden")
        }, function () {
            e.blocker.classList.add("mm-hidden")
        }))
    };
    e.default = u;
    window.MmenuLight = u
}]);