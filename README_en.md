# Reset Media Version — Joomla! Plugin

[Читать на Русском](README.md)

Plugin adds a button for quickly updating the media files version in Joomla.

## A simple way to refresh cached CSS/JS files in Joomla

---

### ⚠️ Problem

When styles and scripts on a website are updated, browsers often load outdated versions from the cache, even if the files on the server have changed. This leads to inconsistencies in how the site is displayed, causing confusion for users or clients.

### ✅ Solution

This plugin adds a button to the main administration dashboard of Joomla (in the `Quick Icon` group), allowing administrators to **manually reset the media files version**. As a result, all CSS and JS resources will be loaded with a new version parameter (`?ver=...`), **forcing browsers and CDNs to fetch the latest files**.

---

### 💡 Key Features

- 🖥️ Adds a button to the Joomla dashboard
- 🔁 Resets the version of static resources (CSS/JS)
- 🧠 Forces browsers to load up-to-date files
- 🎯 Works using Joomla's built-in mechanisms
- 👌 Suitable for production sites
- 🧩 Easy installation as a standard Joomla extension

---

### ⚙️ Requirements

- Joomla >= 4.2 / 5.x
- PHP >= 7.4

---

### 📦 Installation

1. Download the plugin archive.
2. Go to Joomla Admin → Extensions → Manage → Install.
3. Select the `.zip` file and install it like any other extension.
4. The plugin will immediately appear on the admin dashboard.

---

### 📄 License

This plugin is distributed under the **[GNU General Public License v2.0](LICENSE)**.
