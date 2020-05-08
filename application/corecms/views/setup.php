<div class="content-box-large">
    <h1>RadeCMS Installation Guide</h1>
    <p>Welcome to Raddy CMS Installation Tutorial</p>
    <p>The following page will explain how to setup RadeCMS to your local environment using XAMP and Windows OS</p>
    <hr>
    <div class="row">
        <div class="col-lg-8">
            <h4>Step 1 - Modify your hosts file and create your virtual host in apache</h4>
            <p>Lets say you are creating a web site which will has domain name example.com. When developing the site, 
            Your local instance can be mapped to local.example.com. To do that you need to do two things:</p>
            <ul>
                <li>Modify your hosts file - <code>C:\Windows\System32\drivers\etc\hosts</code> and add entry <code>127.0.0.1 local.example.com</code></li>
                <li><p>Open <code>C:\xampp\apache\conf\extra\httpd-vhosts.conf</code> which is apache configuration in your xamp installation, and add this:</p>
<pre>
&lt;VirtualHost *:80&gt;
    DocumentRoot "C:\xampp\htdocs\example.com"
    ServerName local.example.com
&lt;/VirtualHost&gt;
</pre>
                </li>
        </ul>
        </div>
    </div>
</div>