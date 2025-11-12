{
  const e = document;
  e.addEventListener("DOMContentLoaded", function () {
    const t = e.getElementById("r52_acceptcookies-params");
    if (t) {
      const o = JSON.parse(t.dataset.params);
      "Y" == o.settings.switch_on &&
        ((function (t) {
          let s = e.cookie.match(
            new RegExp(
              "(?:^|; )" +
                t.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") +
                "=([^;]*)"
            )
          );
          return s ? decodeURIComponent(s[1]) : void 0;
        })("accept_cookies") ||
          (function (t) {
            let o,
              a = e.querySelector("body"),
              n = e.createElement("section"),
              c = t.text,
              i = t.design,
              r = `\n<div class="r52-a-cookies__inner">\n<div class="r52-a-cookies__body">\n<p>${
                c.mainText
              } ${
                "Y" == t.settings.linkShow
                  ? "<a href=" +
                    c.linkPath +
                    ' target="_blank">' +
                    c.linkText +
                    "</a>"
                  : ""
              }</p>\n<div class="r52-a-cookies__settings">\n<h6 class="r52-a-cookies__s-title">${
                c.settingsTitle
              }</h6>\n<div class="r52-a-cookies__s-body">\n<form class="r52-a-cookies__form">\n<label class="r52-a-cookies__checkbox r52-a-cookies__checkbox--disabled">\n<input type="checkbox" name="aCookiesMin" checked readonly>\n<span>${
                c.settingsCheckbox1Text
              }</span>\n</label>\n<label class="r52-a-cookies__checkbox">\n<input type="checkbox" name="aCookiesAll" checked>\n<span>${
                c.settingsCheckbox2Text
              }</span>\n</label>\n</form>\n</div>\n</div>\n</div>\n<footer class="r52-a-cookies__footer">\n<button class="r52-a-cookies__btn r52-a-cookies__btn-accept">${
                c.btn1Text
              }</button>\n<button class="r52-a-cookies__btn r52-a-cookies__btn--transparent r52-a-cookies__btn-settings">${
                c.btn2Text
              }</button>\n</footer>\n</div>`;
            n.setAttribute("class", "r52-a-cookies"),
              (n.innerHTML = r),
              a.appendChild(n);
            for (let e in i)
              i[e] && n.style.setProperty("--r52-a-cookies-" + e, i[e]);
            switch (i["block-align"]) {
              case "left":
                n.classList.add("r52-a-cookies--left");
                break;
              case "center":
                n.classList.add("r52-a-cookies--center");
                break;
              case "right":
                n.classList.add("r52-a-cookies--right");
            }
            (o = n.querySelector(".r52-a-cookies__settings")).setAttribute(
              "data-height",
              o.offsetHeight
            ),
              o.classList.add("off"),
              n.addEventListener("click", (e) => {
                if (
                  (e.target.classList.contains("r52-a-cookies__btn-settings") &&
                    ((o.style.height = o.dataset.height + "px"),
                    o.classList.remove("off"),
                    e.target.classList.add("r52-a-cookies__btn--disabled")),
                  e.target.classList.contains("r52-a-cookies__btn-accept"))
                ) {
                  let e = new FormData(n.querySelector("form")),
                    t = {};
                  for (let [s, o] of e.entries()) t[s] = o;
                  s("accept_cookies", "aCookiesAll" in t ? "all" : "min", 1),
                    n.classList.remove("active");
                }
              }),
              setTimeout(() => {
                n.classList.add("active");
              }, 1500);
          })(o));
    }
    function s(t, s, o, a = {}) {
      a = { path: "/" };
      let n = new Date();
      n.setFullYear(n.getFullYear() + o), (a.expires = n);
      let c = encodeURIComponent(t) + "=" + encodeURIComponent(s);
      for (let e in a) {
        c += "; " + e;
        let t = a[e];
        !0 !== t && (c += "=" + t);
      }
      e.cookie = c;
    }
  });
}
