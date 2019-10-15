/**
 * @file
 * A concept of how a "View" might look like.
 * Traditionally, views are actually templates, which are being rendered with
 * string interpolation and other methods. This here however is a JSX-based
 * view, which is meant to return what is needed to allow server-side
 * rendering.
 *
 * The idea is that a Controller takes care of the business logic, whilst a
 * view takes care of making data visible in a nice and proper way. There-
 * fore, a View has many methods to properly generate the output which is
 * then sent to the user.
 *
 * Should the user request a regular page load, then the jsx() method is
 * called, which is meant to return hyperscripted nodes - basically,
 * return the result of calling h() many times. The methods xml(), json() and
 * jsonld() should be self-explaining.
 */

// View base
import View from "BIRD3/Foundation/Classes/ViewBase";

// UI
import {Container} from "BIRD3/UI";

// Widgets
import {RecentContent} from "BIRD3/App/Content/Components";
import {ContentTypes} from "BIRD3/App/Content/Constants";
import {RecentCharacters} from "BIRD3/App/Characters/Components";
import {NewsTop, NewsList} from "BIRD3/App/Blog/Components";

export default
class FrontPage extends View {
  constructor(viewData) {
    // should actually be state or props?
    this.viewData = viewData;
  }

  /**
   * This method returns a tree of elements which are to be rendered and then
   * sent to the client. In SSR, we render to a string, and yet valid HTML.
   * In this conceptual example here, we render a container which holds a lot
   * of "recently posted" widgets from the Content, Character and Blog module,
   * where the Blog module also acts as a News module, as blog entries are
   * essentially news entries, should they be marked as such.
   *
   * This tree is returned to the server, so it can be rendered, and sent.
   * @return {[type]} [description]
   */
  jsx() {
    return (<Container>
      <NewsTop/>
      <RecentContent forType={ContentTypes.Artwork}/>
      <RecentCharacters/>
      <RecentContent forType={ContentTypes.Story}/>
      <RecentContent forType={ContentTypes.Music}/>
    </Container>);
  }
}
