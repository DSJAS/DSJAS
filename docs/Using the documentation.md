# Using the DSJAS documentation

If you're not viewing these documentation pages on a site such as GitHub, the text will be quite hard to read, owing to the fact that they are formatted using Markdown - which isn't meant to be read on its own.

So, to render out this documentation, please use ```pip``` to install MKDocs. You can do this using the following command:

```bash
pip install mkdocs
```

Then run:

```bash
mkdocs serve
```

You should run this comand **in the root of the project!** It should **not** be run in the docs directory.

After you have done this, navigate to *localhost:8000* in your browser. You should see some nicely formatted documentation files, organised by category.
