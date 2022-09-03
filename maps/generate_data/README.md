Download latest CSVs into ../data

`run.php` to get list of locations. Any new locations will be appended to `location_data.json`. Any locations that can't be found by Google Maps are saved to `location_issues.json`.

Create CSV from `location_issues.json` by running `php generate_empty_location_csv.php > empty.csv`

Upload `empty.csv` to Google Drive and update the location data. Download file and save as `resolved_locations.csv`.

`merge.php` takes `resolved_locations.csv` and merges the changes into `location_data.json`.

`make_timed_data.php` to create time series JSON for the heatmap.