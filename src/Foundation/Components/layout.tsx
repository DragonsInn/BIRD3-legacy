import {Fragment, Component, h} from "preact";
import helmet from "preact-helmet"
import {render} from "preact-render-to-string";

export default function Layout(info) {
  var markup = render(<helmet {...info} />);
  var head = helmet.rewind();

  return `
    <!DOCTYPE html>
    <html ${head.htmlAttributes.toString()}>
      <head>
        ${head.title.toString()}
        ${head.meta.toString()}
        ${head.link.toString()}
      </head>
      <body>
      </body>
    </html>
  `;
}