!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.postscribe=e():t.postscribe=e()}(this,function(){return function(t){function e(r){if(n[r])return n[r].exports;var o=n[r]={exports:{},id:r,loaded:!1};return t[r].call(o.exports,o,o.exports,e),o.loaded=!0,o.exports}var n={};return e.m=t,e.c=n,e.p="",e(0)}([function(t,e,n){"use strict";var r=n(1),o=function(t){return t&&t.__esModule?t:{default:t}}(r);t.exports=o.default},function(t,e,n){"use strict";function r(){}function o(){var t=h.shift();if(t){var e=f.last(t);e.afterDequeue(),t.stream=i.apply(void 0,t),e.afterStreamStart()}}function i(t,e,n){function i(t){t=n.beforeWrite(t),y.write(t),n.afterWrite(t)}y=new c.default(t,n),y.id=d++,y.name=n.name||y.id,a.streams[y.name]=y;var u=t.ownerDocument,l={close:u.close,open:u.open,write:u.write,writeln:u.writeln};s(u,{close:r,open:r,write:function(){for(var t=arguments.length,e=Array(t),n=0;n<t;n++)e[n]=arguments[n];return i(e.join(""))},writeln:function(){for(var t=arguments.length,e=Array(t),n=0;n<t;n++)e[n]=arguments[n];return i(e.join("")+"\n")}});var f=y.win.onerror||r;return y.win.onerror=function(t,e,r){n.error({msg:t+" - "+e+": "+r}),f.apply(y.win,[t,e,r])},y.write(e,function(){s(u,l),y.win.onerror=f,n.done(),y=null,o()}),y}function a(t,e,n){if(f.isFunction(n))n={done:n};else if("clear"===n)return h=[],y=null,void(d=0);n=f.defaults(n,p),t=/^#/.test(t)?window.document.getElementById(t.substr(1)):t.jquery?t[0]:t;var i=[t,e,n];return t.postscribe={cancel:function(){i.stream?i.stream.abort():i[1]=r}},n.beforeEnqueue(i),h.push(i),y||o(),t.postscribe}e.__esModule=!0;var s=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(t[r]=n[r])}return t};e.default=a;var u=n(2),c=function(t){return t&&t.__esModule?t:{default:t}}(u),l=n(4),f=function(t){if(t&&t.__esModule)return t;var e={};if(null!=t)for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n]);return e.default=t,e}(l),p={afterAsync:r,afterDequeue:r,afterStreamStart:r,afterWrite:r,autoFix:!0,beforeEnqueue:r,beforeWriteToken:function(t){return t},beforeWrite:function(t){return t},done:r,error:function(t){throw new Error(t.msg)},releaseAsync:!1},d=0,h=[],y=null;s(a,{streams:{},queue:h,WriteStream:c.default})},function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function o(t,e){var n=f+e,r=t.getAttribute(n);return l.existy(r)?String(r):r}function i(t,e){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:null,r=f+e;l.existy(n)&&""!==n?t.setAttribute(r,n):t.removeAttribute(r)}e.__esModule=!0;var a=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(t[r]=n[r])}return t},s=n(3),u=function(t){return t&&t.__esModule?t:{default:t}}(s),c=n(4),l=function(t){if(t&&t.__esModule)return t;var e={};if(null!=t)for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n]);return e.default=t,e}(c),f="data-ps-",p="ps-style",d="ps-script",h=function(){function t(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};r(this,t),this.root=e,this.options=n,this.doc=e.ownerDocument,this.win=this.doc.defaultView||this.doc.parentWindow,this.parser=new u.default("",{autoFix:n.autoFix}),this.actuals=[e],this.proxyHistory="",this.proxyRoot=this.doc.createElement(e.nodeName),this.scriptStack=[],this.writeQueue=[],i(this.proxyRoot,"proxyof",0)}return t.prototype.write=function(){var t;for((t=this.writeQueue).push.apply(t,arguments);!this.deferredRemote&&this.writeQueue.length;){var e=this.writeQueue.shift();l.isFunction(e)?this._callFunction(e):this._writeImpl(e)}},t.prototype._callFunction=function(t){var e={type:"function",value:t.name||t.toString()};this._onScriptStart(e),t.call(this.win,this.doc),this._onScriptDone(e)},t.prototype._writeImpl=function(t){this.parser.append(t);for(var e=void 0,n=void 0,r=void 0,o=[];(e=this.parser.readToken())&&!(n=l.isScript(e))&&!(r=l.isStyle(e));)(e=this.options.beforeWriteToken(e))&&o.push(e);o.length>0&&this._writeStaticTokens(o),n&&this._handleScriptToken(e),r&&this._handleStyleToken(e)},t.prototype._writeStaticTokens=function(t){var e=this._buildChunk(t);return e.actual?(e.html=this.proxyHistory+e.actual,this.proxyHistory+=e.proxy,this.proxyRoot.innerHTML=e.html,this._walkChunk(),e):null},t.prototype._buildChunk=function(t){for(var e=this.actuals.length,n=[],r=[],o=[],i=t.length,a=0;a<i;a++){var s=t[a],u=s.toString();if(n.push(u),s.attrs){if(!/^noscript$/i.test(s.tagName)){var c=e++;r.push(u.replace(/(\/?>)/," "+f+"id="+c+" $1")),s.attrs.id!==d&&s.attrs.id!==p&&o.push("atomicTag"===s.type?"":"<"+s.tagName+" "+f+"proxyof="+c+(s.unary?" />":">"))}}else r.push(u),o.push("endTag"===s.type?u:"")}return{tokens:t,raw:n.join(""),actual:r.join(""),proxy:o.join("")}},t.prototype._walkChunk=function(){for(var t=void 0,e=[this.proxyRoot];l.existy(t=e.shift());){var n=1===t.nodeType;if(!(n&&o(t,"proxyof"))){n&&(this.actuals[o(t,"id")]=t,i(t,"id"));var r=t.parentNode&&o(t.parentNode,"proxyof");r&&this.actuals[r].appendChild(t)}e.unshift.apply(e,l.toArray(t.childNodes))}},t.prototype._handleScriptToken=function(t){var e=this,n=this.parser.clear();n&&this.writeQueue.unshift(n),t.src=t.attrs.src||t.attrs.SRC,(t=this.options.beforeWriteToken(t))&&(t.src&&this.scriptStack.length?this.deferredRemote=t:this._onScriptStart(t),this._writeScriptToken(t,function(){e._onScriptDone(t)}))},t.prototype._handleStyleToken=function(t){var e=this.parser.clear();e&&this.writeQueue.unshift(e),t.type=t.attrs.type||t.attrs.TYPE||"text/css",t=this.options.beforeWriteToken(t),t&&this._writeStyleToken(t),e&&this.write()},t.prototype._writeStyleToken=function(t){var e=this._buildStyle(t);this._insertCursor(e,p),t.content&&(e.styleSheet&&!e.sheet?e.styleSheet.cssText=t.content:e.appendChild(this.doc.createTextNode(t.content)))},t.prototype._buildStyle=function(t){var e=this.doc.createElement(t.tagName);return e.setAttribute("type",t.type),l.eachKey(t.attrs,function(t,n){e.setAttribute(t,n)}),e},t.prototype._insertCursor=function(t,e){this._writeImpl('<span id="'+e+'"/>');var n=this.doc.getElementById(e);n&&n.parentNode.replaceChild(t,n)},t.prototype._onScriptStart=function(t){t.outerWrites=this.writeQueue,this.writeQueue=[],this.scriptStack.unshift(t)},t.prototype._onScriptDone=function(t){return t!==this.scriptStack[0]?void this.options.error({msg:"Bad script nesting or script finished twice"}):(this.scriptStack.shift(),this.write.apply(this,t.outerWrites),void(!this.scriptStack.length&&this.deferredRemote&&(this._onScriptStart(this.deferredRemote),this.deferredRemote=null)))},t.prototype._writeScriptToken=function(t,e){var n=this._buildScript(t),r=this._shouldRelease(n),o=this.options.afterAsync;t.src&&(n.src=t.src,this._scriptLoadHandler(n,r?o:function(){e(),o()}));try{this._insertCursor(n,d),n.src&&!r||e()}catch(t){this.options.error(t),e()}},t.prototype._buildScript=function(t){var e=this.doc.createElement(t.tagName);return l.eachKey(t.attrs,function(t,n){e.setAttribute(t,n)}),t.content&&(e.text=t.content),e},t.prototype._scriptLoadHandler=function(t,e){function n(){t=t.onload=t.onreadystatechange=t.onerror=null}function r(){n(),null!=e&&e(),e=null}function o(t){n(),s(t),null!=e&&e(),e=null}function i(t,e){var n=t["on"+e];null!=n&&(t["_on"+e]=n)}var s=this.options.error;i(t,"load"),i(t,"error"),a(t,{onload:function(){if(t._onload)try{t._onload.apply(this,Array.prototype.slice.call(arguments,0))}catch(e){o({msg:"onload handler failed "+e+" @ "+t.src})}r()},onerror:function(){if(t._onerror)try{t._onerror.apply(this,Array.prototype.slice.call(arguments,0))}catch(e){return void o({msg:"onerror handler failed "+e+" @ "+t.src})}o({msg:"remote script failed "+t.src})},onreadystatechange:function(){/^(loaded|complete)$/.test(t.readyState)&&r()}})},t.prototype._shouldRelease=function(t){return!/^script$/i.test(t.nodeName)||!!(this.options.releaseAsync&&t.src&&t.hasAttribute("async"))},t}();e.default=h},function(t,e,n){!function(e,n){t.exports=function(){return function(t){function e(r){if(n[r])return n[r].exports;var o=n[r]={exports:{},id:r,loaded:!1};return t[r].call(o.exports,o,o.exports,e),o.loaded=!0,o.exports}var n={};return e.m=t,e.c=n,e.p="",e(0)}([function(t,e,n){"use strict";var r=n(1),o=function(t){return t&&t.__esModule?t:{default:t}}(r);t.exports=o.default},function(t,e,n){"use strict";function r(t){if(t&&t.__esModule)return t;var e={};if(null!=t)for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n]);return e.default=t,e}function o(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}e.__esModule=!0;var i=n(2),a=r(i),s=n(3),u=r(s),c=n(6),l=function(t){return t&&t.__esModule?t:{default:t}}(c),f=n(5),p={comment:/^<!--/,endTag:/^<\//,atomicTag:/^<\s*(script|style|noscript|iframe|textarea)[\s\/>]/i,startTag:/^</,chars:/^[^<]/},d=function(){function t(){var e=this,n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};o(this,t),this.stream=n;var i=!1,s={};for(var u in a)a.hasOwnProperty(u)&&(r.autoFix&&(s[u+"Fix"]=!0),i=i||s[u+"Fix"]);i?(this._readToken=(0,l.default)(this,s,function(){return e._readTokenImpl()}),this._peekToken=(0,l.default)(this,s,function(){return e._peekTokenImpl()})):(this._readToken=this._readTokenImpl,this._peekToken=this._peekTokenImpl)}return t.prototype.append=function(t){this.stream+=t},t.prototype.prepend=function(t){this.stream=t+this.stream},t.prototype._readTokenImpl=function(){var t=this._peekTokenImpl();if(t)return this.stream=this.stream.slice(t.length),t},t.prototype._peekTokenImpl=function(){for(var t in p)if(p.hasOwnProperty(t)&&p[t].test(this.stream)){var e=u[t](this.stream);if(e)return"startTag"===e.type&&/script|style/i.test(e.tagName)?null:(e.text=this.stream.substr(0,e.length),e)}},t.prototype.peekToken=function(){return this._peekToken()},t.prototype.readToken=function(){return this._readToken()},t.prototype.readTokens=function(t){for(var e=void 0;e=this.readToken();)if(t[e.type]&&!1===t[e.type](e))return},t.prototype.clear=function(){var t=this.stream;return this.stream="",t},t.prototype.rest=function(){return this.stream},t}();e.default=d,d.tokenToString=function(t){return t.toString()},d.escapeAttributes=function(t){var e={};for(var n in t)t.hasOwnProperty(n)&&(e[n]=(0,f.escapeQuotes)(t[n],null));return e},d.supports=a;for(var h in a)a.hasOwnProperty(h)&&(d.browserHasFlaw=d.browserHasFlaw||!a[h]&&h)},function(t,e){"use strict";e.__esModule=!0;var n=!1,r=!1,o=window.document.createElement("div");try{var i="<P><I></P></I>";o.innerHTML=i,e.tagSoup=n=o.innerHTML!==i}catch(t){e.tagSoup=n=!1}try{o.innerHTML="<P><i><P></P></i></P>",e.selfClose=r=2===o.childNodes.length}catch(t){e.selfClose=r=!1}o=null,e.tagSoup=n,e.selfClose=r},function(t,e,n){"use strict";function r(t){var e=t.indexOf("--\x3e");if(e>=0)return new c.CommentToken(t.substr(4,e-1),e+3)}function o(t){var e=t.indexOf("<");return new c.CharsToken(e>=0?e:t.length)}function i(t){if(-1!==t.indexOf(">")){var e=t.match(l.startTag);if(e){var n=function(){var t={},n={},r=e[2];return e[2].replace(l.attr,function(e,o){arguments[2]||arguments[3]||arguments[4]||arguments[5]?arguments[5]?(t[arguments[5]]="",n[arguments[5]]=!0):t[o]=arguments[2]||arguments[3]||arguments[4]||l.fillAttr.test(o)&&o||"":t[o]="",r=r.replace(e,"")}),{v:new c.StartTagToken(e[1],e[0].length,t,n,!!e[3],r.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,""))}}();if("object"===(void 0===n?"undefined":u(n)))return n.v}}}function a(t){var e=i(t);if(e){var n=t.slice(e.length);if(n.match(new RegExp("</\\s*"+e.tagName+"\\s*>","i"))){var r=n.match(new RegExp("([\\s\\S]*?)</\\s*"+e.tagName+"\\s*>","i"));if(r)return new c.AtomicTagToken(e.tagName,r[0].length+e.length,e.attrs,e.booleanAttrs,r[1])}}}function s(t){var e=t.match(l.endTag);if(e)return new c.EndTagToken(e[1],e[0].length)}e.__esModule=!0;var u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};e.comment=r,e.chars=o,e.startTag=i,e.atomicTag=a,e.endTag=s;var c=n(4),l={startTag:/^<([\-A-Za-z0-9_]+)((?:\s+[\w\-]+(?:\s*=?\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/,endTag:/^<\/([\-A-Za-z0-9_]+)[^>]*>/,attr:/(?:([\-A-Za-z0-9_]+)\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))|(?:([\-A-Za-z0-9_]+)(\s|$)+)/g,fillAttr:/^(checked|compact|declare|defer|disabled|ismap|multiple|nohref|noresize|noshade|nowrap|readonly|selected)$/i}},function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}e.__esModule=!0,e.EndTagToken=e.AtomicTagToken=e.StartTagToken=e.TagToken=e.CharsToken=e.CommentToken=e.Token=void 0;var o=n(5),i=(e.Token=function t(e,n){r(this,t),this.type=e,this.length=n,this.text=""},e.CommentToken=function(){function t(e,n){r(this,t),this.type="comment",this.length=n||(e?e.length:0),this.text="",this.content=e}return t.prototype.toString=function(){return"\x3c!--"+this.content},t}(),e.CharsToken=function(){function t(e){r(this,t),this.type="chars",this.length=e,this.text=""}return t.prototype.toString=function(){return this.text},t}(),e.TagToken=function(){function t(e,n,o,i,a){r(this,t),this.type=e,this.length=o,this.text="",this.tagName=n,this.attrs=i,this.booleanAttrs=a,this.unary=!1,this.html5Unary=!1}return t.formatTag=function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n="<"+t.tagName;for(var r in t.attrs)if(t.attrs.hasOwnProperty(r)){n+=" "+r;var i=t.attrs[r];void 0!==t.booleanAttrs&&void 0!==t.booleanAttrs[r]||(n+='="'+(0,o.escapeQuotes)(i)+'"')}return t.rest&&(n+=" "+t.rest),n+=t.unary&&!t.html5Unary?"/>":">",void 0!==e&&null!==e&&(n+=e+"</"+t.tagName+">"),n},t}());e.StartTagToken=function(){function t(e,n,o,i,a,s){r(this,t),this.type="startTag",this.length=n,this.text="",this.tagName=e,this.attrs=o,this.booleanAttrs=i,this.html5Unary=!1,this.unary=a,this.rest=s}return t.prototype.toString=function(){return i.formatTag(this)},t}(),e.AtomicTagToken=function(){function t(e,n,o,i,a){r(this,t),this.type="atomicTag",this.length=n,this.text="",this.tagName=e,this.attrs=o,this.booleanAttrs=i,this.unary=!1,this.html5Unary=!1,this.content=a}return t.prototype.toString=function(){return i.formatTag(this,this.content)},t}(),e.EndTagToken=function(){function t(e,n){r(this,t),this.type="endTag",this.length=n,this.text="",this.tagName=e}return t.prototype.toString=function(){return"</"+this.tagName+">"},t}()},function(t,e){"use strict";function n(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";return t?t.replace(/([^"]*)"/g,function(t,e){return/\\/.test(e)?e+'"':e+'\\"'}):e}e.__esModule=!0,e.escapeQuotes=n},function(t,e){"use strict";function n(t){return t&&"startTag"===t.type&&(t.unary=s.test(t.tagName)||t.unary,t.html5Unary=!/\/>$/.test(t.text)),t}function r(t,e){var r=t.stream,o=n(e());return t.stream=r,o}function o(t,e){var n=e.pop();t.prepend("</"+n.tagName+">")}function i(){var t=[];return t.last=function(){return this[this.length-1]},t.lastTagNameEq=function(t){var e=this.last();return e&&e.tagName&&e.tagName.toUpperCase()===t.toUpperCase()},t.containsTagName=function(t){for(var e,n=0;e=this[n];n++)if(e.tagName===t)return!0;return!1},t}function a(t,e,a){function s(){var e=r(t,a);e&&l[e.type]&&l[e.type](e)}var c=i(),l={startTag:function(n){var r=n.tagName;"TR"===r.toUpperCase()&&c.lastTagNameEq("TABLE")?(t.prepend("<TBODY>"),s()):e.selfCloseFix&&u.test(r)&&c.containsTagName(r)?c.lastTagNameEq(r)?o(t,c):(t.prepend("</"+n.tagName+">"),s()):n.unary||c.push(n)},endTag:function(n){c.last()?e.tagSoupFix&&!c.lastTagNameEq(n.tagName)?o(t,c):c.pop():e.tagSoupFix&&(a(),s())}};return function(){return s(),n(a())}}e.__esModule=!0,e.default=a;var s=/^(AREA|BASE|BASEFONT|BR|COL|FRAME|HR|IMG|INPUT|ISINDEX|LINK|META|PARAM|EMBED)$/i,u=/^(COLGROUP|DD|DT|LI|OPTIONS|P|TD|TFOOT|TH|THEAD|TR)$/i}])}()}()},function(t,e){"use strict";function n(t){return void 0!==t&&null!==t}function r(t){return"function"==typeof t}function o(t,e,n){var r=void 0,o=t&&t.length||0;for(r=0;r<o;r++)e.call(n,t[r],r)}function i(t,e,n){for(var r in t)t.hasOwnProperty(r)&&e.call(n,r,t[r])}function a(t,e){return t=t||{},i(e,function(e,r){n(t[e])||(t[e]=r)}),t}function s(t){try{return Array.prototype.slice.call(t)}catch(n){var e=function(){var e=[];return o(t,function(t){e.push(t)}),{v:e}}();if("object"===(void 0===e?"undefined":p(e)))return e.v}}function u(t){return t[t.length-1]}function c(t,e){return!(!t||"startTag"!==t.type&&"atomicTag"!==t.type||!("tagName"in t)||!~t.tagName.toLowerCase().indexOf(e))}function l(t){return c(t,"script")}function f(t){return c(t,"style")}e.__esModule=!0;var p="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};e.existy=n,e.isFunction=r,e.each=o,e.eachKey=i,e.defaults=a,e.toArray=s,e.last=u,e.isTag=c,e.isScript=l,e.isStyle=f}])}),function(t,e){"function"==typeof define&&define.amd?define(["jquery"],function(n){return e(t,n)}):"object"==typeof module&&"object"==typeof module.exports?module.exports=e(t,require("jquery")):t.lity=e(t,t.jQuery||t.Zepto)}("undefined"!=typeof window?window:this,function(t,e){"use strict";function n(t){var e=N();return q&&t.length?(t.one(q,e.resolve),setTimeout(e.resolve,500)):e.resolve(),e.promise()}function r(t,n,r){if(1===arguments.length)return e.extend({},t);if("string"==typeof n){if(void 0===r)return void 0===t[n]?null:t[n];t[n]=r}else e.extend(t,n);return this}function o(t){var e=t.indexOf("?");e>-1&&(t=t.substr(e+1));for(var n,r=decodeURI(t.split("#")[0]).split("&"),o={},i=0,a=r.length;i<a;i++)r[i]&&(n=r[i].split("="),o[n[0]]=n[1]);return o}function i(t,n){if(!n)return t;if("string"===e.type(n)&&(n=o(n)),t.indexOf("?")>-1){var r=t.split("?");t=r.shift(),n=e.extend({},o(r[0]),n)}return t+"?"+e.param(n)}function a(t,e){var n=t.indexOf("#");return-1===n?e:(n>0&&(t=t.substr(n)),e+t)}function s(t,e,n,r){return e&&e.element().addClass("lity-iframe"),n&&(t=i(t,n)),r&&(t=a(r,t)),'<div class="lity-iframe-container"><iframe frameborder="0" allowfullscreen src="'+t+'"/></div>'}function u(t){return e('<span class="lity-error"/>').append(t)}function c(t,n){var r=n.opener()&&n.opener().data("lity-desc")||"Image with no description",o=e('<img src="'+t+'" alt="'+r+'"/>'),i=N(),a=function(){i.reject(u("Failed loading image"))};return o.on("load",function(){if(0===this.naturalWidth)return a();i.resolve(o)}).on("error",a),i.promise()}function l(t,n){var r,o,i;try{r=e(t)}catch(t){return!1}return!!r.length&&(o=e('<i style="display:none !important"/>'),i=r.hasClass("lity-hide"),n.element().one("lity:remove",function(){o.before(r).remove(),i&&!r.closest(".lity-content").length&&r.addClass("lity-hide")}),r.removeClass("lity-hide").after(o))}function f(t,e){var n=I.exec(t);return!!n&&s("https://www.youtube"+(n[2]||"")+".com/embed/"+n[4]+"?autoplay=1",e,n[5],t)}function p(t,e){var n=R.exec(t);return!!n&&s("https://player.vimeo.com/video/"+n[3]+"?autoplay=1",e,n[4],t)}function d(t,e){var n=W.exec(t);return!!n&&(0!==t.indexOf("http")&&(t="https:"+t),s("https://www.facebook.com/plugins/video.php?href="+t+"&autoplay=1",e,n[4],t))}function h(t,e){var n=H.exec(t);return!!n&&s("https://www.google."+n[3]+"/maps?"+n[6],e,{output:n[6].indexOf("layer=c")>0?"svembed":"embed"},t)}function y(t,e){return s(t,e)}function m(){return A.documentElement.clientHeight?A.documentElement.clientHeight:Math.round(C.height())}function v(t){var e=x();e&&(27===t.keyCode&&e.options("esc")&&e.close(),9===t.keyCode&&g(t,e))}function g(t,e){var n=e.element().find(F),r=n.index(A.activeElement);t.shiftKey&&r<=0?(n.get(n.length-1).focus(),t.preventDefault()):t.shiftKey||r!==n.length-1||(n.get(0).focus(),t.preventDefault())}function T(){e.each(O,function(t,e){e.resize()})}function w(t){1===O.unshift(t)&&(E.addClass("lity-active"),C.on({resize:T,keydown:v})),e("body > *").not(t.element()).addClass("lity-hidden").each(function(){var t=e(this);void 0===t.data(j)&&t.data(j,t.attr(M)||null)}).attr(M,"true")}function b(t){var n;t.element().attr(M,"true"),1===O.length&&(E.removeClass("lity-active"),C.off({resize:T,keydown:v})),O=e.grep(O,function(e){return t!==e}),n=O.length?O[0].element():e(".lity-hidden"),n.removeClass("lity-hidden").each(function(){var t=e(this),n=t.data(j);n?t.attr(M,n):t.removeAttr(M),t.removeData(j)})}function x(){return 0===O.length?null:O[0]}function _(t,n,r,o){var i,a="inline",s=e.extend({},r);return o&&s[o]?(i=s[o](t,n),a=o):(e.each(["inline","iframe"],function(t,e){delete s[e],s[e]=r[e]}),e.each(s,function(e,r){return!r||(!(!r.test||r.test(t,n))||(i=r(t,n),!1!==i?(a=e,!1):void 0))})),{handler:a,content:i||""}}function k(t,o,i,a){function s(t){l=e(t).css("max-height",m()+"px"),c.find(".lity-loader").each(function(){var t=e(this);n(t).always(function(){t.remove()})}),c.removeClass("lity-loading").find(".lity-content").empty().append(l),p=!0,l.trigger("lity:ready",[f])}var u,c,l,f=this,p=!1,d=!1;o=e.extend({},P,o),c=e(o.template),f.element=function(){return c},f.opener=function(){return i},f.content=function(){return l},f.options=e.proxy(r,f,o),f.handlers=e.proxy(r,f,o.handlers),f.resize=function(){p&&!d&&l.css("max-height",m()+"px").trigger("lity:resize",[f])},f.close=function(){if(p&&!d){d=!0,b(f);var t=N();if(a&&(A.activeElement===c[0]||e.contains(c[0],A.activeElement)))try{a.focus()}catch(t){}return l.trigger("lity:close",[f]),c.removeClass("lity-opened").addClass("lity-closed"),n(l.add(c)).always(function(){l.trigger("lity:remove",[f]),c.remove(),c=void 0,t.resolve()}),t.promise()}},u=_(t,f,o.handlers,o.handler),c.attr(M,"false").addClass("lity-loading lity-opened lity-"+u.handler).appendTo("body").focus().on("click","[data-lity-close]",function(t){e(t.target).is("[data-lity-close]")&&f.close()}).trigger("lity:open",[f]),w(f),e.when(u.content).always(s)}function S(t,n,r){t.preventDefault?(t.preventDefault(),r=e(this),t=r.data("lity-target")||r.attr("href")||r.attr("src")):r=e(r);var o=new k(t,e.extend({},r.data("lity-options")||r.data("lity"),n),r,A.activeElement);if(!t.preventDefault)return o}var A=t.document,C=e(t),N=e.Deferred,E=e("html"),O=[],M="aria-hidden",j="lity-"+M,F='a[href],area[href],input:not([disabled]),select:not([disabled]),textarea:not([disabled]),button:not([disabled]),iframe,object,embed,[contenteditable],[tabindex]:not([tabindex^="-"])',P={esc:!0,handler:null,handlers:{image:c,inline:l,youtube:f,vimeo:p,googlemaps:h,facebookvideo:d,iframe:y},template:'<div class="lity" role="dialog" aria-label="Dialog Window (Press escape to close)" tabindex="-1"><div class="lity-wrap" data-lity-close role="document"><div class="lity-loader" aria-hidden="true">Loading...</div><div class="lity-container"><div class="lity-content"></div><button class="lity-close" type="button" aria-label="Close (Press escape to close)" data-lity-close>&times;</button></div></div></div>'},D=/(^data:image\/)|(\.(png|jpe?g|gif|svg|webp|bmp|ico|tiff?)(\?\S*)?$)/i,I=/(youtube(-nocookie)?\.com|youtu\.be)\/(watch\?v=|v\/|u\/|embed\/?)?([\w-]{11})(.*)?/i,R=/(vimeo(pro)?\.com)\/(?:[^\d]+)?(\d+)\??(.*)?$/,H=/((maps|www)\.)?google\.([^\/\?]+)\/?((maps\/?)?\?)(.*)/i,W=/(facebook\.com)\/([a-z0-9_-]*)\/videos\/([0-9]*)(.*)?$/i,q=function(){var t=A.createElement("div"),e={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var n in e)if(void 0!==t.style[n])return e[n];return!1}();return c.test=function(t){return D.test(t)},S.version="3.0.0-dev",S.options=e.proxy(r,S,P),S.handlers=e.proxy(r,S,P.handlers),S.current=x,S.iframe=s,e(A).on("click.lity","[data-lity]",S),S});