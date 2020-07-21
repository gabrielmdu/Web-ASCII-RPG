const fetchAuth = (url, method, body) => {
    const token = localStorage.getItem('api_token');

    let options = {
        headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        method: method
    };

    if (method === 'POST') {
        options.headers['Content-Type'] = 'application/json';
    }

    if (body) {
        options['body'] = body;
    }

    return fetch(url, options);
};

export const fetchGet = url => fetchAuth(url, 'GET');
export const fetchPost = (url, body) => fetchAuth(url, 'POST', JSON.stringify(body));