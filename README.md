It is a example to use PHP, HTML, CSS, JS, MySQL to build a website.

I hope You can learn how to build a MVC structure website by yourself from this example.
I write the codes of login, register and go shopping in this example, however if you want to implement other logical parts, try it by yourself,
I believe you will figure it out.

If you have any questions, welcome to contact me via: yangyang01011001@gmail.com

The Controller is Main_Controller.php, it is responsible for all the logical parts, like jump to different pages when you
click buttons, store data into Database when you register and so on.
The Views are all the template files: login_page.template, product_page.template, register_page.template and payment_opt_page.template. These templates are the combination of PHP, JS, HTML and CSS. They become different pages when you access the website. You can create other templates if you want to extend the functions of the website.
Database is being controlled by Main_Controller.php and db_function.php together. db_function.php acts as Model here, it contains all the funtions that Controller needs, like store data into DB, get data from DB, change data in DB and so on.

header.php is just a header for each templates, you can change or delete it as your demands.

You need to set up Database first when you try this example on your local server. I use phpadmin to setup Database, search it on Google, you will find out a lot of useful info online.

You may also need to add directories like img(put imgs in it), css(CSS files) and so on.

Try it and have fun!
