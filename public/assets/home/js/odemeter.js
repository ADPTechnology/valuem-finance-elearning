var __assign =
        (this && this.__assign) ||
        function () {
            return (
                (__assign =
                    Object.assign ||
                    function (t) {
                        for (var n, e = 1, i = arguments.length; e < i; e++)
                            for (var r in (n = arguments[e]))
                                Object.prototype.hasOwnProperty.call(n, r) &&
                                    (t[r] = n[r]);
                        return t;
                    }),
                __assign.apply(this, arguments)
            );
        },
    rAF =
        window.requestAnimationFrame ||
        function (t) {
            window.setTimeout(t, 1e3 / 60);
        },
    Odometer = (function () {
        function t(t) {
            (this.version = "1.0"),
                (this.defaults = { duration: 0.8, lastDigitDelay: 0.25 }),
                (this.cell_digits = null),
                (this.options = __assign(__assign({}, this.defaults), t)),
                (this.cell_digits = null);
        }
        return (
            (t.prototype.render = function (t, n) {
                var e = this.options,
                    i = !1;
                if (!this.cell_digits) {
                    if (
                        ((i = !0), !document.querySelector("style[odometer]"))
                    ) {
                        var r = document.createElement("style");
                        r.setAttribute("odometer", "odometer"),
                            (r.innerHTML =
                                ".odometer-numbers{display:inline-flex;line-height:100%;overflow-y:hidden}.odometer-numbers>span{display:flex;flex-direction:column;justify-content:start;align-items:center;height:1em;will-change:transform;transform:translateY(0)}"),
                            document.head.appendChild(r);
                    }
                    (t.innerHTML = '<div class="odometer-numbers"></div>'),
                        (this.cell_digits = []);
                }
                for (
                    var o = '<span style="color:transparent">0</span>',
                        s = "transform ".concat(e.duration, "s ease-out"),
                        a = this.cell_digits.length;
                    a < n.length;
                    a++
                ) {
                    var l = document.createElement("span");
                    (l.style.transition = s),
                        (l.innerHTML = i ? "" : o),
                        t.firstChild && t.firstChild.appendChild(l),
                        this.cell_digits.push({
                            container: l,
                            current: void 0,
                            position: i ? 1 : 0,
                            new: !0,
                        });
                }
                function d(t, n) {
                    t.position--,
                        t.container.appendChild(n),
                        (t.lastTimeAdd = +new Date()),
                        t.new
                            ? ((t.new = !1),
                              rAF(function () {
                                  t.container.style.transform =
                                      "translateY(".concat(t.position, "em)");
                              }))
                            : (t.container.style.transform =
                                  "translateY(".concat(t.position, "em)"));
                }
                var c,
                    u,
                    m = Math.max(n.length, this.cell_digits.length),
                    h = function () {
                        c = a < n.length ? n.charAt(a) : null;
                        var t = f.cell_digits[a];
                        t.current != c &&
                            ((t.current = c),
                            ((u = document.createElement("span")).innerHTML =
                                null === c ? o : c),
                            t.container.children.length < 4
                                ? d(t, u)
                                : (function (t, n) {
                                      t.nextToAdd &&
                                          (d(t, t.nextToAdd),
                                          clearTimeout(t.lastTimer),
                                          (t.nextToAdd = null));
                                      var i = +new Date(),
                                          r =
                                              1e3 * e.lastDigitDelay -
                                              (i - t.lastTimeAdd);
                                      e.lastDigitDelay <= 0 ||
                                      i - t.lastTimeAdd >= 1.05 * r
                                          ? (d(t, n), (t.nextToAdd = null))
                                          : ((t.nextToAdd = n),
                                            (t.lastTimer = setTimeout(
                                                function () {
                                                    d(t, t.nextToAdd),
                                                        (t.nextToAdd = null);
                                                },
                                                1e3 * e.duration
                                            )));
                                  })(t, u),
                            clearTimeout(t.timerClean),
                            (t.timerClean = setTimeout(function () {
                                (t.timerClean = null),
                                    t.container.children.length < 3 ||
                                        ((t.container.style.transition =
                                            "none"),
                                        rAF(function () {
                                            for (
                                                t.position = -1;
                                                t.container.children.length > 1;

                                            )
                                                t.container.removeChild(
                                                    t.container.firstChild
                                                );
                                            var n =
                                                document.createElement("span");
                                            (n.innerHTML = o),
                                                t.container.insertBefore(
                                                    n,
                                                    t.container.firstChild
                                                ),
                                                (t.container.style.transform =
                                                    "translateY(".concat(
                                                        t.position,
                                                        "em)"
                                                    )),
                                                rAF(function () {
                                                    t.container.style.transition =
                                                        s;
                                                });
                                        }));
                            }, 1e3 *
                                ((e.duration || 0.8) + (e.duration || 0.25)) +
                                2500)));
                    },
                    f = this;
                for (a = 0; a < m; a++) h();
            }),
            t
        );
    })();
export { Odometer };
