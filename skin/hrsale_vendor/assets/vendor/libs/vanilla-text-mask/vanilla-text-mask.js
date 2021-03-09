!function(e,r){for(var t in r)e[t]=r[t]}(window,function(e){var r={};function t(n){if(r[n])return r[n].exports;var o=r[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,t),o.l=!0,o.exports}return t.m=e,t.c=r,t.d=function(e,r,n){t.o(e,r)||Object.defineProperty(e,r,{enumerable:!0,get:n})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,r){if(1&r&&(e=t(e)),8&r)return e;if(4&r&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(t.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&r&&"string"!=typeof e)for(var o in e)t.d(n,o,function(r){return e[r]}.bind(null,o));return n},t.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(r,"a",r),r},t.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},t.p="",t(t.s=737)}({312:function(e,r,t){e.exports=function(e){function r(n){if(t[n])return t[n].exports;var o=t[n]={exports:{},id:n,loaded:!1};return e[n].call(o.exports,o,o.exports,r),o.loaded=!0,o.exports}var t={};return r.m=e,r.c=t,r.p="",r(0)}([function(e,r,t){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function o(e){var r=e.inputElement,t=(0,a.default)(e),n=function(e){var r=e.target.value;return t.update(r)};return r.addEventListener("input",n),t.update(r.value),{textMaskInputElement:t,destroy:function(){r.removeEventListener("input",n)}}}Object.defineProperty(r,"__esModule",{value:!0}),r.conformToMask=void 0,r.maskInput=o;var i=t(2);Object.defineProperty(r,"conformToMask",{enumerable:!0,get:function(){return n(i).default}});var a=n(t(5));r.default=o},function(e,r){"use strict";Object.defineProperty(r,"__esModule",{value:!0}),r.placeholderChar="_",r.strFunction="function"},function(e,r,t){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};r.default=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:u,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:a,t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};if(!(0,o.isArray)(r)){if((void 0===r?"undefined":n(r))!==i.strFunction)throw new Error("Text-mask:conformToMask; The mask property must be an array.");r=r(e,t),r=(0,o.processCaretTraps)(r).maskWithoutCaretTraps}var l=t.guide,s=void 0===l||l,f=t.previousConformedValue,c=void 0===f?u:f,d=t.placeholderChar,v=void 0===d?i.placeholderChar:d,p=t.placeholder,h=void 0===p?(0,o.convertMaskToPlaceholder)(r,v):p,m=t.currentCaretPosition,y=t.keepCharPositions,g=!1===s&&void 0!==c,b=e.length,C=c.length,P=h.length,k=r.length,x=b-C,O=x>0,T=m+(O?-x:0),M=T+Math.abs(x);if(!0===y&&!O){for(var S=u,w=T;w<M;w++)h[w]===v&&(S+=v);e=e.slice(0,T)+S+e.slice(T,b)}for(var _=e.split(u).map((function(e,r){return{char:e,isNew:r>=T&&r<M}})),j=b-1;j>=0;j--){var V=_[j].char;V!==v&&V===h[j>=T&&C===k?j-x:j]&&_.splice(j,1)}var A=u,E=!1;e:for(var N=0;N<P;N++){var F=h[N];if(F===v){if(_.length>0)for(;_.length>0;){var I=_.shift(),L=I.char,R=I.isNew;if(L===v&&!0!==g){A+=v;continue e}if(r[N].test(L)){if(!0===y&&!1!==R&&c!==u&&!1!==s&&O){for(var J=_.length,W=null,q=0;q<J;q++){var z=_[q];if(z.char!==v&&!1===z.isNew)break;if(z.char===v){W=q;break}}null!==W?(A+=L,_.splice(W,1)):N--}else A+=L;continue e}E=!0}!1===g&&(A+=h.substr(N,P));break}A+=F}if(g&&!1===O){for(var B=null,D=0;D<A.length;D++)h[D]===v&&(B=D);A=null!==B?A.substr(0,B+1):u}return{conformedValue:A,meta:{someCharsRejected:E}}};var o=t(3),i=t(1),a=[],u=""},function(e,r,t){"use strict";function n(e){return Array.isArray&&Array.isArray(e)||e instanceof Array}Object.defineProperty(r,"__esModule",{value:!0}),r.convertMaskToPlaceholder=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:i,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:o.placeholderChar;if(!n(e))throw new Error("Text-mask:convertMaskToPlaceholder; The mask property must be an array.");if(-1!==e.indexOf(r))throw new Error("Placeholder character must not be used as part of the mask. Please specify a character that is not present in your mask as your placeholder character.\n\nThe placeholder character that was received is: "+JSON.stringify(r)+"\n\nThe mask that was received is: "+JSON.stringify(e));return e.map((function(e){return e instanceof RegExp?r:e})).join("")},r.isArray=n,r.isString=function(e){return"string"==typeof e||e instanceof String},r.isNumber=function(e){return"number"==typeof e&&void 0===e.length&&!isNaN(e)},r.processCaretTraps=function(e){for(var r=[],t=void 0;-1!==(t=e.indexOf(a));)r.push(t),e.splice(t,1);return{maskWithoutCaretTraps:e,indexes:r}};var o=t(1),i=[],a="[]"},function(e,r){"use strict";Object.defineProperty(r,"__esModule",{value:!0}),r.default=function(e){var r=e.previousConformedValue,o=void 0===r?n:r,i=e.previousPlaceholder,a=void 0===i?n:i,u=e.currentCaretPosition,l=void 0===u?0:u,s=e.conformedValue,f=e.rawValue,c=e.placeholderChar,d=e.placeholder,v=e.indexesOfPipedChars,p=void 0===v?t:v,h=e.caretTrapIndexes,m=void 0===h?t:h;if(0===l||!f.length)return 0;var y=f.length,g=o.length,b=d.length,C=s.length,P=y-g,k=P>0;if(P>1&&!k&&0!==g)return l;var x=0,O=void 0,T=void 0;if(!k||o!==s&&s!==d){var M=s.toLowerCase(),S=f.toLowerCase().substr(0,l).split(n).filter((function(e){return-1!==M.indexOf(e)}));T=S[S.length-1];var w=a.substr(0,S.length).split(n).filter((function(e){return e!==c})).length,_=d.substr(0,S.length).split(n).filter((function(e){return e!==c})).length!==w,j=void 0!==a[S.length-1]&&void 0!==d[S.length-2]&&a[S.length-1]!==c&&a[S.length-1]!==d[S.length-1]&&a[S.length-1]===d[S.length-2];!k&&(_||j)&&w>0&&d.indexOf(T)>-1&&void 0!==f[l]&&(O=!0,T=f[l]);for(var V=p.map((function(e){return M[e]})).filter((function(e){return e===T})).length,A=S.filter((function(e){return e===T})).length,E=d.substr(0,d.indexOf(c)).split(n).filter((function(e,r){return e===T&&f[r]!==e})).length+A+V+(O?1:0),N=0,F=0;F<C&&(x=F+1,M[F]===T&&N++,!(N>=E));F++);}else x=l-P;if(k){for(var I=x,L=x;L<=b;L++)if(d[L]===c&&(I=L),d[L]===c||-1!==m.indexOf(L)||L===b)return I}else if(O){for(var R=x-1;R>=0;R--)if(s[R]===T||-1!==m.indexOf(R)||0===R)return R}else for(var J=x;J>=0;J--)if(d[J-1]===c||-1!==m.indexOf(J)||0===J)return J};var t=[],n=""},function(e,r,t){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function o(e,r){document.activeElement===e&&(h?m((function(){return e.setSelectionRange(r,r,v)}),0):e.setSelectionRange(r,r,v))}function i(e){if((0,f.isString)(e))return e;if((0,f.isNumber)(e))return String(e);if(null==e)return d;throw new Error("The 'value' provided to Text Mask needs to be a string or a number. The value received was:\n\n "+JSON.stringify(e))}Object.defineProperty(r,"__esModule",{value:!0});var a=Object.assign||function(e){for(var r=1;r<arguments.length;r++){var t=arguments[r];for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n])}return e},u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};r.default=function(e){var r={previousConformedValue:void 0,previousPlaceholder:void 0};return{state:r,update:function(t){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:e,v=n.inputElement,h=n.mask,m=n.guide,y=n.pipe,g=n.placeholderChar,b=void 0===g?c.placeholderChar:g,C=n.keepCharPositions,P=void 0!==C&&C,k=n.showMask,x=void 0!==k&&k;if(void 0===t&&(t=v.value),t!==r.previousConformedValue){(void 0===h?"undefined":u(h))===p&&void 0!==h.pipe&&void 0!==h.mask&&(y=h.pipe,h=h.mask);var O=void 0,T=void 0;if(h instanceof Array&&(O=(0,f.convertMaskToPlaceholder)(h,b)),!1!==h){var M=i(t),S=v.selectionEnd,w=r.previousConformedValue,_=r.previousPlaceholder,j=void 0;if((void 0===h?"undefined":u(h))===c.strFunction){if(!1===(T=h(M,{currentCaretPosition:S,previousConformedValue:w,placeholderChar:b})))return;var V=(0,f.processCaretTraps)(T),A=V.maskWithoutCaretTraps,E=V.indexes;T=A,j=E,O=(0,f.convertMaskToPlaceholder)(T,b)}else T=h;var N={previousConformedValue:w,guide:m,placeholderChar:b,pipe:y,placeholder:O,currentCaretPosition:S,keepCharPositions:P},F=(0,s.default)(M,T,N),I=F.conformedValue,L=(void 0===y?"undefined":u(y))===c.strFunction,R={};L&&(!1===(R=y(I,a({rawValue:M},N)))?R={value:w,rejected:!0}:(0,f.isString)(R)&&(R={value:R}));var J=L?R.value:I,W=(0,l.default)({previousConformedValue:w,previousPlaceholder:_,conformedValue:J,placeholder:O,rawValue:M,currentCaretPosition:S,placeholderChar:b,indexesOfPipedChars:R.indexesOfPipedChars,caretTrapIndexes:j}),q=J===O&&0===W,z=x?O:d,B=q?z:J;r.previousConformedValue=B,r.previousPlaceholder=O,v.value!==B&&(v.value=B,o(v,W))}}}}};var l=n(t(4)),s=n(t(2)),f=t(3),c=t(1),d="",v="none",p="object",h="undefined"!=typeof navigator&&/Android/i.test(navigator.userAgent),m="undefined"!=typeof requestAnimationFrame?requestAnimationFrame:setTimeout}])},737:function(e,r,t){"use strict";t.r(r);var n=t(312);t.d(r,"vanillaTextMask",(function(){return n}))}}));