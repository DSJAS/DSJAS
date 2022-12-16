package config

import (
	"encoding/json"
	"io"
	"os"
)

// Load unmarshals the DSJAS config from the path provided. If the config is
// not found at path, no error is returned, but the pointer to the config
// struct is nil. This is later used to detect and set up the installer.
func Load(path string) (*Config, error) {
	f, err := os.Open(path)
	if err != nil {
		if os.IsNotExist(err) {
			return nil, nil
		}
		return nil, err
	}
	buf, err := io.ReadAll(f)
	if err != nil {
		return nil, err
	}
	cfg := new(Config)
	err = json.Unmarshal(buf, cfg)
	if err != nil {
		return cfg, err
	}

	return cfg, nil
}

// Store saves cfg to path, ensuring to lock the contained RWMutex for reading
// first.
func Store(cfg *Config, path string) error {
	cfg.Mut.RLock()
	defer cfg.Mut.RUnlock()

	f, err := os.Create(path)
	if err != nil {
		return err
	}

	buf, err := json.Marshal(cfg)
	if err != nil {
		return err
	}

	_, err = f.Write(buf)
	if err != nil {
		return err
	}

	return nil
}
