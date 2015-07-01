var hljs = require('highlight.js/lib/highlight');

hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash'));
hljs.registerLanguage('cpp', require('highlight.js/lib/languages/cpp'));
//hljs.registerLanguage('cs', require('highlight.js/lib/languages/cs'));
hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));
hljs.registerLanguage('markdown', require('highlight.js/lib/languages/markdown'));
//hljs.registerLanguage('http', require('highlight.js/lib/languages/http'));
//hljs.registerLanguage('ini', require('highlight.js/lib/languages/ini'));
hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
//hljs.registerLanguage('json', require('highlight.js/lib/languages/json'));
//hljs.registerLanguage('objectivec', require('highlight.js/lib/languages/objectivec'));
hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
hljs.registerLanguage('scss', require('highlight.js/lib/languages/scss'));
//hljs.registerLanguage('sql', require('highlight.js/lib/languages/sql'));
//hljs.registerLanguage('xml', require('highlight.js/lib/languages/xml'));

module.exports = hljs;
