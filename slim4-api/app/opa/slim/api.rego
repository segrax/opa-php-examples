package slim.api

default allow = false

# OPA Bundle
allow {
    input.path = ["opa", "bundles", name]
    input.token.sub == "opa"
}

# Allow a user to access their own private end point
allow {
    input.method == "GET"
    input.path = ["welcome", userid ]
    userid == input.token.sub
}

# Allow anyone (inluding unauthed) users access to the public end point
allow {
    input.path = ["public"]
    input.method == "GET"
}
