# Weather Forecast Collector

A Laravel application that collects daily weather forecasts from Open-Meteo and WeatherAPI. It stores hourly and daily temperature and precipitation data in PostgreSQL and provides a simple JSON API.

## 1. Installation and Setup

### Requirements

- Docker and Docker Compose
- A free API key from [WeatherAPI](https://www.weatherapi.com/)

### Installation

- Clone the repository and enter the project folder.
- Add your WeatherAPI key to the `.env` file: `WEATHERAPI_KEY=your_key_here`
- Start the application using Docker Compose:

```bash
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose restart
```

- The application automatically runs the database migrations and adds one default location (Drama, Greece).
- The application is available at [http://localhost:8000](http://localhost:8000).

### Running the Fetch

- The `scheduler` container runs `forecasts:fetch` every day at 06:00 AM.
- To run it manually:

```bash
docker compose exec app php artisan forecasts:fetch
```

### API

The application provides two endpoints:

- `GET /api/locations`: Returns all active locations.
- `GET /api/locations/{id}/forecasts`: Returns the latest daily forecasts from each provider for a location.

For more specific results, the forecasts endpoint supports the following optional parameters:

- `type=daily` (default) or `type=hourly`
- `provider=open_meteo` or `provider=weatherapi`
- `issued_on=YYYY-MM-DD`: Gets forecasts from a specific date.

Example:

[http://localhost:8000/api/locations/1/forecasts?type=hourly&provider=weatherapi](http://localhost:8000/api/locations/1/forecasts?type=hourly&provider=weatherapi)

## 2. Implementation Choices

### Technologies

- PHP 8.4
- Laravel 13
- PostgreSQL 16
- Docker and Docker Compose

### Docker Setup

The project uses three Docker services:

- `app`: Runs the Laravel API using `php artisan serve`.
- `scheduler`: Runs the Laravel scheduler using `php artisan schedule:work`.
- `db`: Runs PostgreSQL.

### Database Structure

The database has four main tables:

- `locations`: Stores the locations for which forecasts are collected. The `is_active` field allows a location to be disabled without deleting its data, making it easier to support more cities in the future.
- `forecast_fetches`: Stores each API request, including its provider, location, date, status and any error message.
- `hourly_forecasts`: Stores hourly temperature and precipitation data.
- `daily_forecasts`: Stores daily minimum and maximum temperatures and total precipitation.

### Forecast History

Instead of overwriting old forecasts, the application keeps every forecast it collects.

Weather providers can change their forecasts from day to day. For this reason, each forecast is stored with an `issued_on` date.

This allows us to compare forecasts for the same day that were collected at different times. By default, the API returns the latest forecast.

For daily data, I store minimum and maximum temperatures instead of averages.

Both providers offer minimum temperature, maximum temperature and total precipitation, so I use the same data fields for both.

### Weather Providers

The application uses a simple `WeatherProvider` interface with two methods: `name()` and `fetch()`.

Each provider (`OpenMeteoProvider` and `WeatherApiProvider`) handles its own API request and converts the response into a common format.

Both providers use the same field names and units (°C and mm). Hourly times are stored in UTC, while daily dates use the local date of each location.

The rest of the application does not depend on a specific provider.

### Error Handling

- Each provider runs independently. If one fails, the other can still save its data.
- Failed requests are stored in `forecast_fetches` with their status and error message.
- Running the command more than once on the same day does not create duplicate records.
- Database unique constraints help prevent duplicates.

### API Limits

The application makes two API requests per location each day, one for each provider.

## 3. Incomplete Requirements

- No automated tests.
- No daily monitoring or regular checks of the fetch process.
- No validation for API query parameters. For example, an unknown `type` falls back to `daily`.
- No API authentication.

## 4. Future Improvements

- Add more weather data, such as humidity and wind.
- Store actual weather data for the current day.
- Add more weather providers.
- Add user accounts with the option to save favorite cities.
- Combine data from all providers to produce more accurate forecasts.
- Send alerts to users about extreme weather changes, such as sudden temperature changes.
- Compare previous forecasts with actual weather data to measure provider accuracy.

## 5. Additional Notes

- The default location is Drama, Greece. More locations can be added to the `locations` table. The application collects forecasts for all active locations.
- I used an AI assistant during development, as allowed by the assignment.