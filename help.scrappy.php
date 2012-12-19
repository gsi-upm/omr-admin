<p>Scrappy is a tool that allows extracting information from web pages and producing RDF data. It uses the scraping ontology to define the mappings between HTML contents and RDF data.</p>

<div class="panel">
					<p>An example of mapping is shown next, which allows extracting all titles
from <a href="http://www.elmundo.es">www.elmundo.es</a>:</p>

<pre>dc: http://purl.org/dc/elements/1.1/
rdf: http://www.w3.org/1999/02/22-rdf-syntax-ns#
sioc: http://rdfs.org/sioc/ns#
sc: http://lab.gsi.dit.upm.es/scraping.rdf#
*:
  rdf:type: sc:Fragment
  sc:selector:
    *:
      rdf:type: sc:UriSelector
      rdf:value: "http://www.elmundo.es/"
  sc:identifier:
    *:
      rdf:type: sc:BaseUriSelector
  sc:subfragment:
    *:
      sc:type: sioc:Post
      sc:selector:
        *:
          rdf:type: sc:CssSelector
          rdf:value: ".noticia h2, .noticia h3, .noticia h4"
      sc:identifier:
        *:
          rdf:type: sc:CssSelector
          rdf:value: "a"
          sc:attribute: "href"
      sc:subfragment:
        *:
          sc:type:     rdf:Literal
          sc:relation: dc:title
          sc:selector:
            *:
              rdf:type:  sc:CssSelector
              rdf:value: "a"</pre>

<p>(The above code is serialized using YARF format, supported by LightRDF gem,
as well as RDFXML, JSON, NTriples formats, which can also be used to define
the mappings).</p>

<h4>SYNOPSIS:</h4>

<p>A knowledge base of mappings can be defined by storing RDF files inside
~/.scrappy/extractors folder. Then, the command-line tool can be used to
get RDF data from web sites. You can get help on this tool by typing:</p>

<pre>$ scrappy --help</pre>

<p>Scrappy offers many different interfaces to get RDF data from a web page:</p>
<ul>
<li>
<p>Command-line interface:</p>

<pre>$ scrappy -g example.com</pre>
</li>
<li>
<p>Web Admin interface:</p>

<pre>$ scrappy -a
Launching Scrappy Web Admin (browse http://localhost:3434)...
== Sinatra/1.1.3 has taken the stage on 3434 for production with backup from Thin</pre>

<p>Then point your browser to <a href="http://localhost:3434">localhost:3434</a> for additional directions.</p>
</li>
<li>
<p>Web Service interface:</p>

<pre>$ scrappy -s
Launching Scrappy Web Server...
== Sinatra/1.1.3 has taken the stage on 3434 for production with backup from Thin</pre>

<p>Then use the service in the same way as the Web Admin but for read-only
operations.</p>
</li>
<li>
<p>Ruby interface:</p>

<p>You can use Scrappy in a Ruby program by requiring the gem:</p>

<pre>require 'rubygems'
require 'scrappy'

# Parse a knowledge base
kb = RDF::Parser.parse :yarf, open("https://github.com/josei/scrappy/raw/master/kb/elmundo.yarf").read

# Set kb as default knowledge base
Scrappy::Agent::Options.kb = kb

# Create an agent
agent = Scrappy::Agent.new

# Get RDF output
output = agent.request :method=&gt;:get, :uri=&gt;'http://www.elmundo.es'

# Output all titles from the web page
titles = output.find([], Node('dc:title'), nil)
titles.each { |title| puts title }</pre>
</li>
<li>
<p>RDF repository:</p>

<p>Sesame functionality has been included in Scrappy. You can configure the
repository options by editing the file config.yml placed the folder
.scrappy, in your home dir. An example of this file can be found at the end
of this README.</p>

<p>You can get the data for a certain period of time, by using the time (-t,–time) option:</p>

<pre>$ scrappy -g example.org -t 3</pre>

<p>This would get all the data stored in Sesame for example.org in the last 3
minutes.</p>
</li>
<li>
<p>Sample config.yml</p>

<pre># This is a sample configuration file, with the options to communicate with Sesame using Scrappy
repository:
  # The host were Sesame is. Do not add the trailing '/'
  host: http://localhost

  # The port for the connection
  port: 8080

  # The time to consider the data in the repository valid, in minutes
  time: 15

  # The name of the repository
  repository: memory

  # The format to communicate with the repository
  format: ntriples

  # You can use any of the following formats:
  # rdfxml, ntriples, turtle, n3, trix, trig</pre>
</li>
</ul><h4>INSTALL:</h4>

<p>Install it as any other gem:</p>

<pre>$ gem install scrappy</pre>

<p>The gem also requires raptor library (in Debian systems: sudo aptitude
install raptor-utils), which is used for outputting different RDF
serialization formats.</p>

<p>PNG output of RDF graphs requires Graphviz (in Debian systems: sudo
aptitude install graphviz).</p>

<p>In order to use Sesame, you will need to install it. Further instructions
can be found in the openRDF website, more precisely, in <a href="http://www.openrdf.org/doc/sesame2/users/ch06.html">www.openrdf.org/doc/sesame2/users/ch06.html</a>
.</p>
					</div>