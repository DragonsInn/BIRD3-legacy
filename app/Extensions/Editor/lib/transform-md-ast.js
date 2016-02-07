import {
    Tokenizer as MarkdownTokenizer,
    TokenType
} from "miniMarkdown";

// Global grammar selection
var Grammar = [
    new TokenType('pre', {
        grammar: [],
        start: "```\n",
        end: "```\n"
    }),
    new TokenType('code', {surround: '`'}),
    new TokenType('italic', {surround: '_'}),
    new TokenType('bold', {surround: '*'}),
    new TokenType('strike', {surround: '~'}),
    new TokenType('heading', {
        constraint: function (text) {
            var match = text.match(/^#{1,6}\s.*\n/)
            return match && match[0].length;
        }
    }),
    new TokenType('quote', {
        constraint: function (text) {
            var match = text.match(/^>{1,}\s.*\n/)
            return match && match[0].length;
        }
    }),
    new TokenType('list', {
        constraint: function (text) {
            var match = text.match(/^[\-\*]{1,}\s.*\n/)
            return match && match[0].length;
        }
    }),
    new TokenType('mention', {
        constraint: function(text) {
            var match = text.match(/^@[a-z0-9_\-\.]*/i)
            return match && match[0].length;
        }
    }),
    new TokenType('uri', {
        constraint: function(text) {
            var match = text.match(/^(((http|ftp)s?:\/\/|mailto:)[^\s]+)/);
            return match && match[0].length;
        }
    }),
    new TokenType('email', {
        constraint: function(text) {
            //var regex = require("email-regex")({exact: true});
            var regex = /^\S+@\S+\.\S+/i;
            var match = text.match(regex);
            return match && match[0].length;
        }
    }),
    new TokenType('hashtag', {
        constraint: function(text) {
            var match = text.match(/^#[a-z0-9]{1,}/i)
            return match && match[0].length;
        }
    })
];

// Helper to find and wrap according to grammar.
function wrapToken(types, text) {
    var starts = [], ends = [];
    types = (types instanceof Array ? types : types.split(" "));
    types.forEach(function(type){
        for(var i=0; i<Grammar.length; i++) {
            var spec = Grammar[i];
            if(type == spec.type) {
                starts.push(spec.start);
                ends.push(spec.end);
                break;
            }
        }
    });
    var out = "";
    out += starts.join("");
    out += text;
    out += ends.join("");
    return out;
}

export class MDToken {
    constructor(name, content) {
        this.name = name;
        this.content = content;
    }

    toString() {
        return wrapToken(this.name, this.content);
    }

    identify() {
        return this.name;
    }
}

export function ParseMarkdown(str) {
    var mdTokenizer = new MarkdownTokenizer(str, Grammar);
    var AST = mdTokenizer.run();
    return Tokenize(AST);
}

export function Tokenize(
    ASTNodes,
    className = "",
    stack = []
) {
    ASTNodes.forEach(function(node){
        if(typeof node.children == "undefined") {
            var prefix = [className, node.type].join(" ").trim();
            var token = new MDToken(prefix, node.text);
            stack.push(token);
        } else {
            Tokenize(node.children, node.type, stack);
        }
    });
    return stack;
}
