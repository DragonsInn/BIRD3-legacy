export default function url(u) {
    var scheme = location.protocol+"//";
    var host = location.hostname;
    var pathname = ( u.charAt(0) == "/"
        ? u
        : location.pathname+"/"+u
    );
    return scheme+host+pathname;
}
