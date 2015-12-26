import oo from "o.o";
/*
    - Grab all the markdown docs inside the docs folder.
    - Read them and create a nice sidebar of them.
    - Display the document currently selected.

    Yup, this is an SPA inside a MPA. o.o

    The only thing that PHP provides is the base HTML layout.
    Therefore, we'll attach to the proper "onDone" thing.
*/

export default class DocsController {
    constructor(docs) {
        this.docs = docs;
    }
    render() {
        var docs = this.docs;
        var $topics = oo("#DocTopics");
        var $docBody = oo("#DocBody");
        docs.forEach(function(topic, topicId){
            var $t = (<p>
                <strong innerHTML={topic.title}/>
                <ul>
                    {topic.entries.map(entry => {
                        var entryTitle = entry.shortTitle || entry.title;
                        var onEntryClick = function(){
                            $docBody.html(entry.body);
                        };
                        return (<li
                            innerHTML={entryTitle}
                            onclick={onEntryClick}
                        />);
                    })}
                </ul>
            </p>);
            $topics.append($t);
        });
    }
}
