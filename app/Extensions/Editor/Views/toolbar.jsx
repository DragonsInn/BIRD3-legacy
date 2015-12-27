import _ from "microdash";

// Quickly do an uppercase.
function ucfirst(str) {
    return (
        str.charAt(0).toUpperCase()
    )+(
        str.substr(1)
    );
}

/**
 * Generate a set of formatter buttons.
 *
 * @param  {Object} set     Object of name, func, html and data.
 * @param  {Object} data    Overall rendering data.
 * @return {Array}          HTML Button elements.
 */
function generateButtons(set, data) {
    return set.map((f) => {
        let elem = (<button
            className="btn btn-default"
            title={ucfirst(f.name)}
            data-func={f.func}
            data-toggle={(_.isString(f.toggle) ? f.toggle : "tooltip")}
            data-placement={data.placement}
            data-container="body"
        >{f.html}</button>);
        if(_.isPlainObject(f.data)) {
            _.extend(elem.dataset, f.data);
        }
        return elem;
    });
}

/**
 * Generate an editor toolbar based on rendering data.
 *
 * @param  {Object} data Rendering data
 * @return {HTML} Output HTML
 */
export default function(data) {
    const FontSettings = (
        <div className={("btn-group btn-group-"+data.groupSize)} role="group" aria-label="Font settings">
            {generateButtons([ // Formatters
                {name: "Bold", func: "bold", html: (<b>B</b>)},
                {name: "italic", func: "italic", html: (<i>I</i>)},
                {
                    name: "Font color",
                    func:"font_color",
                    toggle:"popover",
                    data: {dismiss: true},
                    html: (<span>
                        <i className="glyphicon glyphicon-text-color" aria-hidden="true"></i>
                        <span className="sr-only">Font color</span>
                    </span>)
                }, {
                    name: "Background color",
                    func: "bg_color",
                    html: (<span>
                        <i className="glyphicon glyphicon-text-background" aria-hidden="true"></i>
                        <span className="sr-only">Background color</span>
                    </span>)
                }
            ], data)}
        </div>
    );

    const textDisplay = (
        <div className={("btn-group btn-group-"+groupSize)} role="group" aria-label="Text display">
            {generateButtons([
                {name: "Code", func: "code", html:(<span>
                    <i className="fa fa-code" aria-hidden="true"></i>
                    <span className="sr-only">Code</span>
                </span>)},
                {name: "Code block", func: "code_block", html:(<span>
                    <i className="fa fa-file-code-o" aria-hidden="true"></i>
                    <span className="sr-only">Code block</span>
                </span>)},
                {name: "Quote", func: "quote", html:(<span>
                    <i className="fa fa-quote-right" aria-hidden="true"></i>
                    <span className="sr-only">Quote</span>
                </span>)},
                {name: "Ordered list", func: "ol", html:(<span>
                    <i className="fa fa-list-ol" aria-hidden="true"></i>
                    <span className="sr-only">Ordered List</span>
                </span>)},
                {name: "Unordered list", func: "ul", html:(<span>
                    <i className="fa fa-list-ul" aria-hidden="true"></i>
                    <span className="sr-only">Unordered List</span>
                </span>)}
            ], data)}
        </div>
    );

    const Links = (
        <div className={("btn-group btn-group-"+groupSize)} role="group" aria-label="Links">
            {generateButtons([
                {name: "Link", func: "link", html:(<span>
                    <i className="glyphicon glyphicon-link" aria-hidden="true"></i>
                    <span className="sr-only">Link</span>
                </span>)},
                {name: "Image", func: "image", html:(<span>
                    <i className="fa fa-picture-o" aria-hidden="true"></i>
                    <span className="sr-only">Image</span>
                </span>)}
            ], data)}
        </div>
    );

    const Options = (
        <div className={("btn-group btn-group-"+groupSize)} role="group" aria-label="Options">
            <button
                className="btn btn-primary"
                id={(data.wid+"_preview")}
                title="Preview"
                data-toggle="tooltip"
                data-placement={data.placement}
                data-container="body"
            >
                <i className="fa fa-eye" aria-hidden="true"></i>
                <span className="sr-only">Preview</span>
            </button>
            <a
                title="Help and Info"
                href="#"
                className="btn btn-info"
                data-toggle="tooltip"
                data-placement={data.placement}
                data-container="body"
            >
                <i className="fa fa-question-circle" aria-hidden="true"></i>
                <span className="sr-only">Help and info</span>
            </a>
        </div>
    );
    const toolbar = (
        <div
            id={(data.wid+"_toolbar")}
            className="btn-toolbar"
            role="toolbar"
            aria-label="Formatting options"
        />
    );

    // Build the child tree.
    toolbar.appendChild(Formatting);
    if(data.textDisplay) {
        toolbar.appendChild(TextDisplay);
    }
    toolbar.appendChild(options);

    return toolbar;
}
