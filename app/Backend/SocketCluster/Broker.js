process.title = "BIRD3: SC Store";

import SCRedis from "sc-redis";
export function run(store) {
    SCRedis.attach(store);
}
