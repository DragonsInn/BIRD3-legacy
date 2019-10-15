import {GlobSync} from "glob";

export default function(pattern: String): String {
  let glob = GlobSync(pattern);
  let pairs: Map<String, String>;

  for (let file of glob.found) {
    let id = file
      .replace(/\.*$/, "")
      .replace(/\./g, "_")
      .replace(/\//g, ".");
    pairs[id] = `loadScript("${id}");`;
  }

  let innerObj: String;
  for(let id in pairs) {
    innerObj += `    "${id}": () => { ${pairs[id]} },`;
  }

  return [
    "export default function(moduleid) {",
    "  return ({",
    innerObj,
    "  })[moduleid] || (new Promise((resolve, reject) => {",
    "    reject(`Module '${moduleid}' was not found.`);",
    "  }));"
  ].join("\n");
}