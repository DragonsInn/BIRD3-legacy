/**
 * Transforms a markdown-it AST into a simplified array.
 * That array should only contain objects of format:
 *
 * {
 *     type: "Name of the token",
 *     content: "...",
 * }
 */
var transform = function(AST, parentStack, lineStore) {
    parentStack = parentStack || [];
    lineStore = lineStore || [];
    var previous = null;
    var tokens = [];

    // Helper
    function addToken(lineId, nodeData) {
        if(typeof lineStore[lineId] == "undefined") {
            lineStore[lineId] = [];
        }
        lineStore[lineId].push(new mdASTNode(nodeData));
    }
    function makeTokens() {
        for(var l=0; l<lineStore.length; l++) {
            if(typeof lineStore[l] == "undefined") {
                lineStore[l] = [new mdASTNode({
                    type: "empty",
                    content: "\n"
                })];
            }
            var line = lineStore[l];
            for(var n=0; n<line.length; n++) {
                tokens.push(line[n]);
            }
        }
    }

    for(var node_id=0; node_id<AST.length; node_id++) {
        var ASTNode = AST[node_id];
        var lineNr = null;
        previous = (previous==null ? ASTNode : AST[node_id-1]);
        if(ASTNode.map != null && lineNr == null) {
            lineNr = ASTNode.map[0];
        } else {
            if(previous.map != null) {
                lineNr = previous.map[0];
            } else {
                for(var i=parentStack.length-1; i+1>0; i--) {
                    console.log("i", i)
                    if(parentStack[i].map != null) lineNr = parentStack[i].map[0];
                }
            }
            if(lineNr == null) {
                console.log("I...cant find a line.", {
                    ASTNode: ASTNode,
                    previous: previous,
                    parentStack: parentStack
                });
            }
        }
        if(ASTNode.markup != '' && ASTNode.nesting != -1) {
            addToken(lineNr, {
                type: ASTNode.tag,
                content: ASTNode.markup+" "
            });
        }
        if(ASTNode.children != null) {
            parentStack.push(ASTNode);
            transform(ASTNode.children, parentStack, lineStore);
        } else {
            if(ASTNode.type == "text") {
                // Text! Let's find the proper name for it.
                var parent = previous;
                for(var i=parentStack.length-1; i+1>0; i--) {
                    // Either, parent being the previous token will
                    // already result in a valid, tag'ed token.
                    // Alternatively, we pop the parent stack till it can pop no' mo'.
                    if(parentStack[i].tag != '') {
                        parent = parentStack[i];
                        break;
                    }
                }
                if(parent.tag == '' && parentStack.length == 0) {
                    throw new Error("The parentStack has ran out and no matching node was found.");
                }
                addToken(lineNr,{
                    type: parent.tag,
                    content: ASTNode.content
                });
            }
        }
    }
    makeTokens();
    console.log(tokens, lineStore)
    return tokens;
}

var mdASTNode = function(node) {
    this.node = node;
}
mdASTNode.prototype.toString = function() {
    return this.node.content;
}
mdASTNode.prototype.identify = function() {
    return "ldt-"+this.node.type;
}

module.exports = transform;
module.exports.mdASTNode = mdASTNode;
