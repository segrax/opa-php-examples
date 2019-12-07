package slim.api

default allow = false

# OPA Bundle
allow {
    input.path = ["opa", "bundles", "{name}"]
    input.token.sub == "opa"
}

# Allow use to access their account
allow {
    input.method == "GET"
    input.path = ["welcome", userid ]
    userid == input.token.sub
}

# Allow authed user to create a location
allow {
    input.path = ["public"]
    input.method == "GET"
}
