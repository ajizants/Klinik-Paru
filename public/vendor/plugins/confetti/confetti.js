var JSConfetti = (function () {
    "use strict";
    function t(t, e) {
        if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function");
    }
    function e(t, e) {
        for (var i = 0; i < e.length; i++) {
            var n = e[i];
            (n.enumerable = n.enumerable || !1),
                (n.configurable = !0),
                "value" in n && (n.writable = !0),
                Object.defineProperty(t, n.key, n);
        }
    }
    function i(t, i, n) {
        return i && e(t.prototype, i), n && e(t, n), t;
    }
    function n(t) {
        return +t.replace(/px/, "");
    }
    function s(t, e) {
        var i =
                arguments.length > 2 && void 0 !== arguments[2]
                    ? arguments[2]
                    : 0,
            n = Math.random() * (e - t) + t;
        return Math.floor(n * Math.pow(10, i)) / Math.pow(10, i);
    }
    function o(t) {
        return t[s(0, t.length)];
    }
    var a = [
        "#fcf403",
        "#62fc03",
        "#f4fc03",
        "#03e7fc",
        "#03fca5",
        "#a503fc",
        "#fc03ad",
        "#fc03c2",
    ];
    function r(t) {
        return Math.log(t) / Math.log(1920);
    }
    var h = (function () {
        function e(i) {
            t(this, e);
            var n = i.initialPosition,
                a = i.direction,
                h = i.confettiRadius,
                c = i.confettiColors,
                u = i.emojis,
                l = i.emojiSize,
                d = i.canvasWidth,
                f = s(0.9, 1.7, 3) * r(d);
            (this.confettiSpeed = { x: f, y: f }),
                (this.finalConfettiSpeedX = s(0.2, 0.6, 3)),
                (this.rotationSpeed = u.length
                    ? 0.01
                    : s(0.03, 0.07, 3) * r(d)),
                (this.dragForceCoefficient = s(5e-4, 9e-4, 6)),
                (this.radius = { x: h, y: h }),
                (this.initialRadius = h),
                (this.rotationAngle =
                    "left" === a ? s(0, 0.2, 3) : s(-0.2, 0, 3)),
                (this.emojiSize = l),
                (this.emojiRotationAngle = s(0, 2 * Math.PI)),
                (this.radiusYUpdateDirection = "down");
            var m =
                "left" === a
                    ? (s(82, 15) * Math.PI) / 180
                    : (s(-15, -82) * Math.PI) / 180;
            (this.absCos = Math.abs(Math.cos(m))),
                (this.absSin = Math.abs(Math.sin(m)));
            var v = s(-150, 0),
                p = {
                    x: n.x + ("left" === a ? -v : v) * this.absCos,
                    y: n.y - v * this.absSin,
                };
            (this.currentPosition = Object.assign({}, p)),
                (this.initialPosition = Object.assign({}, p)),
                (this.color = u.length ? null : o(c)),
                (this.emoji = u.length ? o(u) : null),
                (this.createdAt = new Date().getTime()),
                (this.direction = a);
        }
        return (
            i(e, [
                {
                    key: "draw",
                    value: function (t) {
                        var e = this.currentPosition,
                            i = this.radius,
                            n = this.color,
                            s = this.emoji,
                            o = this.rotationAngle,
                            a = this.emojiRotationAngle,
                            r = this.emojiSize,
                            h = window.devicePixelRatio;
                        n
                            ? ((t.fillStyle = n),
                              t.beginPath(),
                              t.ellipse(
                                  e.x * h,
                                  e.y * h,
                                  i.x * h,
                                  i.y * h,
                                  o,
                                  0,
                                  2 * Math.PI
                              ),
                              t.fill())
                            : s &&
                              ((t.font = "".concat(r, "px serif")),
                              t.save(),
                              t.translate(h * e.x, h * e.y),
                              t.rotate(a),
                              (t.textAlign = "center"),
                              t.fillText(s, 0, 0),
                              t.restore());
                    },
                },
                {
                    key: "updatePosition",
                    value: function (t, e) {
                        var i = this.confettiSpeed,
                            n = this.dragForceCoefficient,
                            s = this.finalConfettiSpeedX,
                            o = this.radiusYUpdateDirection,
                            a = this.rotationSpeed,
                            r = this.createdAt,
                            h = this.direction,
                            c = e - r;
                        i.x > s && (this.confettiSpeed.x -= n * t),
                            (this.currentPosition.x +=
                                i.x *
                                ("left" === h ? -this.absCos : this.absCos) *
                                t),
                            (this.currentPosition.y =
                                this.initialPosition.y -
                                i.y * this.absSin * c +
                                (0.00125 * Math.pow(c, 2)) / 2),
                            (this.rotationSpeed -= this.emoji
                                ? 1e-4
                                : 1e-5 * t),
                            this.rotationSpeed < 0 && (this.rotationSpeed = 0),
                            this.emoji
                                ? (this.emojiRotationAngle +=
                                      (this.rotationSpeed * t) % (2 * Math.PI))
                                : "down" === o
                                ? ((this.radius.y -= t * a),
                                  this.radius.y <= 0 &&
                                      ((this.radius.y = 0),
                                      (this.radiusYUpdateDirection = "up")))
                                : ((this.radius.y += t * a),
                                  this.radius.y >= this.initialRadius &&
                                      ((this.radius.y = this.initialRadius),
                                      (this.radiusYUpdateDirection = "down")));
                    },
                },
                {
                    key: "getIsVisibleOnCanvas",
                    value: function (t) {
                        return this.currentPosition.y < t + 100;
                    },
                },
            ]),
            e
        );
    })();
    function c() {
        var t = document.createElement("canvas");
        return (
            (t.style.position = "fixed"),
            (t.style.width = "100%"),
            (t.style.height = "100%"),
            (t.style.top = "0"),
            (t.style.left = "0"),
            (t.style.zIndex = "1000"),
            (t.style.pointerEvents = "none"),
            document.body.appendChild(t),
            t
        );
    }
    function u(t) {
        var e = t.confettiRadius,
            i = void 0 === e ? 6 : e,
            n = t.confettiNumber,
            s = void 0 === n ? t.confettiesNumber || (t.emojis ? 40 : 250) : n,
            o = t.confettiColors,
            r = void 0 === o ? a : o,
            h = t.emojis,
            c = void 0 === h ? t.emojies || [] : h,
            u = t.emojiSize,
            l = void 0 === u ? 80 : u;
        return (
            t.emojies &&
                console.error(
                    "emojies argument is deprecated, please use emojis instead"
                ),
            t.confettiesNumber &&
                console.error(
                    "confettiesNumber argument is deprecated, please use confettiNumber instead"
                ),
            {
                confettiRadius: i,
                confettiNumber: s,
                confettiColors: r,
                emojis: c,
                emojiSize: l,
            }
        );
    }
    var l = (function () {
        function e(i) {
            var n = this;
            t(this, e),
                (this.canvasContext = i),
                (this.shapes = []),
                (this.promise = new Promise(function (t) {
                    return (n.resolvePromise = t);
                }));
        }
        return (
            i(e, [
                {
                    key: "getBatchCompletePromise",
                    value: function () {
                        return this.promise;
                    },
                },
                {
                    key: "addShapes",
                    value: function () {
                        var t;
                        (t = this.shapes).push.apply(t, arguments);
                    },
                },
                {
                    key: "complete",
                    value: function () {
                        var t;
                        return (
                            !this.shapes.length &&
                            (null === (t = this.resolvePromise) ||
                                void 0 === t ||
                                t.call(this),
                            !0)
                        );
                    },
                },
                {
                    key: "processShapes",
                    value: function (t, e, i) {
                        var n = this,
                            s = t.timeDelta,
                            o = t.currentTime;
                        this.shapes = this.shapes.filter(function (t) {
                            return (
                                t.updatePosition(s, o),
                                t.draw(n.canvasContext),
                                !i || t.getIsVisibleOnCanvas(e)
                            );
                        });
                    },
                },
            ]),
            e
        );
    })();
    return (function () {
        function e() {
            var i =
                arguments.length > 0 && void 0 !== arguments[0]
                    ? arguments[0]
                    : {};
            t(this, e),
                (this.activeConfettiBatches = []),
                (this.canvas = i.canvas || c()),
                (this.canvasContext = this.canvas.getContext("2d")),
                (this.requestAnimationFrameRequested = !1),
                (this.lastUpdated = new Date().getTime()),
                (this.iterationIndex = 0),
                (this.loop = this.loop.bind(this)),
                requestAnimationFrame(this.loop);
        }
        return (
            i(e, [
                {
                    key: "loop",
                    value: function () {
                        var t, e, i, s, o;
                        (this.requestAnimationFrameRequested = !1),
                            (t = this.canvas),
                            (e = window.devicePixelRatio),
                            (i = getComputedStyle(t)),
                            (s = n(i.getPropertyValue("width"))),
                            (o = n(i.getPropertyValue("height"))),
                            t.setAttribute("width", (s * e).toString()),
                            t.setAttribute("height", (o * e).toString());
                        var a = new Date().getTime(),
                            r = a - this.lastUpdated,
                            h = this.canvas.offsetHeight,
                            c = this.iterationIndex % 10 == 0;
                        (this.activeConfettiBatches =
                            this.activeConfettiBatches.filter(function (t) {
                                return (
                                    t.processShapes(
                                        { timeDelta: r, currentTime: a },
                                        h,
                                        c
                                    ),
                                    !c || !t.complete()
                                );
                            })),
                            this.iterationIndex++,
                            this.queueAnimationFrameIfNeeded(a);
                    },
                },
                {
                    key: "queueAnimationFrameIfNeeded",
                    value: function (t) {
                        this.requestAnimationFrameRequested ||
                            this.activeConfettiBatches.length < 1 ||
                            ((this.requestAnimationFrameRequested = !0),
                            (this.lastUpdated = t || new Date().getTime()),
                            requestAnimationFrame(this.loop));
                    },
                },
                {
                    key: "addConfetti",
                    value: function () {
                        for (
                            var t =
                                    arguments.length > 0 &&
                                    void 0 !== arguments[0]
                                        ? arguments[0]
                                        : {},
                                e = u(t),
                                i = e.confettiRadius,
                                n = e.confettiNumber,
                                s = e.confettiColors,
                                o = e.emojis,
                                a = e.emojiSize,
                                r = this.canvas.getBoundingClientRect(),
                                c = r.width,
                                d = r.height,
                                f = (5 * d) / 7,
                                m = { x: 0, y: f },
                                v = { x: c, y: f },
                                p = new l(this.canvasContext),
                                y = 0;
                            y < n / 2;
                            y++
                        ) {
                            var g = new h({
                                    initialPosition: m,
                                    direction: "right",
                                    confettiRadius: i,
                                    confettiColors: s,
                                    confettiNumber: n,
                                    emojis: o,
                                    emojiSize: a,
                                    canvasWidth: c,
                                }),
                                C = new h({
                                    initialPosition: v,
                                    direction: "left",
                                    confettiRadius: i,
                                    confettiColors: s,
                                    confettiNumber: n,
                                    emojis: o,
                                    emojiSize: a,
                                    canvasWidth: c,
                                });
                            p.addShapes(g, C);
                        }
                        return (
                            this.activeConfettiBatches.push(p),
                            this.queueAnimationFrameIfNeeded(),
                            p.getBatchCompletePromise()
                        );
                    },
                },
                {
                    key: "clearCanvas",
                    value: function () {
                        this.activeConfettiBatches = [];
                    },
                },
                {
                    key: "destroyCanvas",
                    value: function () {
                        this.canvas.remove();
                    },
                },
            ]),
            e
        );
    })();
})();
