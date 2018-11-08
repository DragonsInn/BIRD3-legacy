# Servers

BIRD3 defines a server as being a program that "delivers to the world". So, a HTTP server would deliver a website and WebSockets to the world, really.

* Each folder represents a *protocol* OR *purpose*.
* Each server runs exactly *one* protocol or purpose.
* The files within each protocol folder may be arranged as needed.
* Server entrypoint scripts are in here, actually.
* Each server must inherit from `BIRD3/Foundation/Classes/BaseServer`, so that it provides an RPC interface.

## Servers:
* SocketCluster
  - HTTP
  - HTTPS
  - WS
  - WSS
  - WebRTC (forwarded for Voice Chat)
* mailin
  - IMAP
  - SMTP
* ldapjs
  - LDAP
  - LDAPS
* dns2
  - DNS
* ssh2
  - SSH
* RanvierMUD
  - TELNET
  - HPROSE (so we can map it to WebSockets)
* BIRD3 Chat
  - IRC
  - HPROSE (for WebSockets mapping)
  - WebRTC (Voice Chat)
