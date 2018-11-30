module.exports = {
  development: {
    client: 'postgresql',
    connection: {
      host: "db",
      database: process.env.APP_NAME || "bird3",
      user: "pgdocker",
      password: "pgdocker"
    }
  },

  production: {
    client: 'postgresql',
    connection: {
      host: "db",
      database: 'my_db',
      user:     'username',
      password: 'password'
    },
    pool: {
      min: 2,
      max: 10
    },
    migrations: {
      tableName: 'knex_migrations'
    }
  }
};
