# Hello ExpressionEngine Conference 2018!

Prior to coming to the workshop, here are a few steps to follow prior to the workshop.

1. Install a fresh ExpressionEngine Core instance on your machine. 
2. Ensure you have NPM installed on your machine. This command should return your version.

```
npm --version
```

2. Install the workshop addon by copying the /system/user/addons/workshop directory into the same directory in your ExpressionEngine Core installation
3. Move the assets directory of this repo into your base EE Core directory.
4. In the terminal window, locate your ExpressionEngine Core base directory and run these commands

```
cd assets
npm install
npm run dev
```
You should receive a success message, and you'll notice two new directories created. /assets/dist and /assets/node_modules

5. Create a template group called home, and a template called index with this HTML.

```
<!DOCTYPE html>
<html>
    <head>  
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/dist/css/index.css">
    </head>
    <body>
        <div class="display-flex flex-center container" id="app">
            <div class="row">
                <div class="col col-md-6 offset-md-3 text-center">
                    <h1>Hello EE Conference 2018!</h1>
                    <p>Prior to starting our workshop, this content should be centered in the middle of the screen. If not, please visit me 10 minutes prior to the start of the workshop.</p>
                    <p>Clicking the link below should display some JSON</p>
                    <a href="/api/orders/index" class="btn btn-primary" target="_blank">Link to API</a>
                </div>
            </div>
        </div>

        <script src="assets/dist/js/index.js"></script>
    </body>
</html>
```
