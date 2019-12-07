package system.authz

default allow = false

allow {
    input.identity == "MyToken"
}
